<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Project;
use App\Http\Requests\EvaluationRequest;
use Illuminate\Http\Request;
use App\Models\ProjectMetric;
use GuzzleHttp\Client;
use Barryvdh\DomPDF\Facade\Pdf;

class EvaluationController extends Controller
{
    /**
     * Mostrar listado de evaluaciones.
     */
    public function index()
    {
        $evaluations = Evaluation::with('project')->paginate(10);
        return view('evaluations.index', compact('evaluations'));
    }

    /**
     * Mostrar formulario para crear una nueva evaluación.
     */
    public function create()
    {
        $projects = Project::pluck('name', 'id');
        return view('evaluations.create', compact('projects'));
    }

    /**
     * Almacenar una nueva evaluación en la base de datos.
     */
    public function store(EvaluationRequest $request)
    {
        $data = $request->validated();
        Evaluation::create($data);

        return redirect()->route('evaluations.index')
                         ->with('success', 'Evaluación creada correctamente.');
    }

    /**
     * Mostrar una evaluación específica.
     */
    public function viewById(Request $request)
{
    $id = $request->query('id');

    if (empty($id) || !is_numeric($id)) {
        return redirect()->back()->withErrors(['id' => 'Por favor ingresa un ID de reporte válido.']);
    }

    // Reutilizamos el método show (evita duplicar lógica)
    return $this->show($id);
}

public function show($id)
{
    // Buscar registro en project_metrics (o el modelo que uses)
    $registro = ProjectMetric::find($id);

    if (!$registro) {
        return redirect()->back()->withErrors(['error' => "No se encontró el reporte con ID {$id}"]);
    }

    $metricas = [
        'Funcionalidad' => $registro->funcionalidad,
        'Eficiencia' => $registro->eficiencia,
        'Compatibilidad' => $registro->compatibilidad,
        'Usabilidad' => $registro->usabilidad,
        'Fiabilidad' => $registro->fiabilidad,
        'Seguridad' => $registro->seguridad,
        'Mantenibilidad' => $registro->mantenibilidad,
        'Portabilidad' => $registro->portabilidad,
    ];

    return view('evaluations.show', compact('registro', 'metricas'));
}
    /**
     * Mostrar formulario para editar una evaluación existente.
     */
    public function edit(Evaluation $evaluation)
    {
        $projects = Project::pluck('name', 'id');
        return view('evaluations.edit', compact('evaluation', 'projects'));
    }

    /**
     * Actualizar una evaluación en la base de datos.
     */
    public function update(EvaluationRequest $request, Evaluation $evaluation)
    {
        $data = $request->validated();
        $evaluation->update($data);

        return redirect()->route('evaluations.index')
                         ->with('success', 'Evaluación actualizada correctamente.');
    }

    /**
     * Eliminar una evaluación.
     */
    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return redirect()->route('evaluations.index')
                         ->with('success', 'Evaluación eliminada correctamente.');
    }

    public function generarMetricas(Request $request)
{
    try {
        $client = new Client([
            'base_uri' => env('COMET_API_URL', 'https://api.cometapi.com'),
        ]);

        // Recuperamos el código fuente analizado desde la sesión
        $codigo = session('codigo', '');
        if (empty($codigo)) {
            return response()->json([
                'success' => false,
                'error' => 'No se encontró código fuente en la sesión.'
            ]);
        }

        // Pedimos a la IA que genere métricas numéricas (JSON)
        $response = $client->post('/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('COMET_API_KEY'),
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model' => env('COMET_DEFAULT_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Eres un auditor experto en métricas de calidad de software ISO/IEC 25023. 
                        Devuelve estrictamente un JSON con las siguientes claves numéricas (0-100): 
                        lineas_analizadas, funcionalidad, eficiencia, compatibilidad, usabilidad, fiabilidad, seguridad, mantenibilidad, portabilidad. 
                        No incluyas texto adicional ni formato fuera del JSON."
                    ],
                    [
                        'role' => 'user',
                        'content' => "Analiza el siguiente código y genera métricas de calidad:\n\n{$codigo}"
                    ],
                ],
            ],
        ]);

        $result = json_decode($response->getBody(), true);

        // Extraer el contenido de la IA
        $raw = $result['choices'][0]['message']['content'] ?? '{}';

        // Aseguramos que sea JSON válido
        $metricas = json_decode($raw, true);
        if (!is_array($metricas)) {
            throw new \Exception('La IA no devolvió un formato JSON válido.');
        }

        // Guardar en la base de datos
        $registro = ProjectMetric::create([
            'project_id'       => 1, // Puedes reemplazar por el ID real del proyecto
            'lineas_analizadas'=> $metricas['lineas_analizadas'] ?? 0,
            'funcionalidad'    => $metricas['funcionalidad'] ?? 0,
            'eficiencia'       => $metricas['eficiencia'] ?? 0,
            'compatibilidad'   => $metricas['compatibilidad'] ?? 0,
            'usabilidad'       => $metricas['usabilidad'] ?? 0,
            'fiabilidad'       => $metricas['fiabilidad'] ?? 0,
            'seguridad'        => $metricas['seguridad'] ?? 0,
            'mantenibilidad'   => $metricas['mantenibilidad'] ?? 0,
            'portabilidad'     => $metricas['portabilidad'] ?? 0,
            'resumen'          => 'Métricas generadas automáticamente por IA en la fecha ' . now(),
        ]);

        return response()->json([
            'success' => true,
            'metricas' => $metricas,
            'registro_id' => $registro->id,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ]);
    }
}

public function generarPDF($id)
{
    // Buscar el registro por ID en project_metrics
    $registro = \App\Models\ProjectMetric::findOrFail($id);

    // Asignar datos con valores por defecto
    $codigo = $registro->codigo ?? 'No disponible';
    $analisis = $registro->analisis ?? 'Sin análisis disponible';
    $resumen = $registro->resumen ?? 'Sin resumen disponible';

    // Armar arreglo de métricas ISO/IEC 25023
    $metricas = (object) [
        'funcionalidad' => $registro->funcionalidad ?? 0,
        'eficiencia' => $registro->eficiencia ?? 0,
        'compatibilidad' => $registro->compatibilidad ?? 0,
        'usabilidad' => $registro->usabilidad ?? 0,
        'fiabilidad' => $registro->fiabilidad ?? 0,
        'seguridad' => $registro->seguridad ?? 0,
        'mantenibilidad' => $registro->mantenibilidad ?? 0,
        'portabilidad' => $registro->portabilidad ?? 0,
        'lineas_analizadas' => $registro->lineas_analizadas ?? 0,
    ];

    // Cargar vista con los datos
$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('evaluacion.reporte', compact(
    'registro', 'codigo', 'analisis', 'resumen', 'metricas'
))
->setPaper('A4', 'portrait')
->setOption([
    'isHtml5ParserEnabled' => true,
    'isRemoteEnabled' => true, // permite cargar imágenes desde asset() o URL
]);
}


}