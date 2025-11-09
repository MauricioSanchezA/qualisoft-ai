<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProjectMetric;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CodeAnalysisController extends Controller
{
    /**
     * Muestra el formulario de evaluación.
     */
    public function index()
    {
        return view('evaluacion.index');
    }

    /**
     * Envía el código a la API para su evaluación según ISO/IEC 25010.
     */
public function evaluar(Request $request)
{
$request->validate([
        'codigo' => 'required|string',
    ]);

    $codigo = $request->input('codigo');

    try {
        $client = new \GuzzleHttp\Client([
            'base_uri' => env('COMET_API_URL', 'https://api.cometapi.com'),
        ]);

        // 🔹 Petición a Comet API
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
                        'content' => "Eres **Qualisoft AI-Eval**, un auditor técnico experto en las normas ISO/IEC 25010 y 25023.
                        Genera el resultado en formato JSON con este esquema exacto:

                        {
                          \"funcionalidad\": <número entre 0 y 100>,
                          \"eficiencia\": <número entre 0 y 100>,
                          \"compatibilidad\": <número entre 0 y 100>,
                          \"usabilidad\": <número entre 0 y 100>,
                          \"fiabilidad\": <número entre 0 y 100>,
                          \"seguridad\": <número entre 0 y 100>,
                          \"mantenibilidad\": <número entre 0 y 100>,
                          \"portabilidad\": <número entre 0 y 100>,
                          \"analisis\": \"texto descriptivo detallado del análisis técnico\",
                          \"resumen\": \"breve resumen general del resultado\"
                        }

                        No incluyas nada fuera del JSON."
                    ],
                    [
                        'role' => 'user',
                        'content' => "Evalúa el siguiente código según ISO/IEC 25010 y 25023:\n\n{$codigo}",
                    ],
                ],
            ],
        ]);

        $result = json_decode($response->getBody(), true);
        $rawOutput = $result['choices'][0]['message']['content'] ?? '';

        // 🔹 Intentar decodificar JSON que devuelve la IA
        $jsonData = json_decode($rawOutput, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($jsonData)) {
            // Si no devolvió JSON válido, tratamos de extraer datos manualmente
            $jsonData = [
                'funcionalidad'   => 0,
                'eficiencia'      => 0,
                'compatibilidad'  => 0,
                'usabilidad'      => 0,
                'fiabilidad'      => 0,
                'seguridad'       => 0,
                'mantenibilidad'  => 0,
                'portabilidad'    => 0,
                'analisis'        => $rawOutput,
                'resumen'         => 'Análisis generado sin formato JSON válido.',
            ];
        }

        // 🔹 Calcular líneas
        $lineasAnalizadas = substr_count($codigo, "\n") + 1;

        // 🔹 Guardar en BD
        $registro = \App\Models\ProjectMetric::create([
            'project_id'        => 1, // Cambia según el proyecto real
            'lineas_analizadas' => $lineasAnalizadas,
            'funcionalidad'     => $jsonData['funcionalidad'] ?? 0,
            'eficiencia'        => $jsonData['eficiencia'] ?? 0,
            'compatibilidad'    => $jsonData['compatibilidad'] ?? 0,
            'usabilidad'        => $jsonData['usabilidad'] ?? 0,
            'fiabilidad'        => $jsonData['fiabilidad'] ?? 0,
            'seguridad'         => $jsonData['seguridad'] ?? 0,
            'mantenibilidad'    => $jsonData['mantenibilidad'] ?? 0,
            'portabilidad'      => $jsonData['portabilidad'] ?? 0,
            'codigo'            => $codigo,
            'analisis'          => $jsonData['analisis'] ?? $rawOutput,
            'resumen'           => $jsonData['resumen'] ?? '',
        ]);

        // 🔹 Guardar para mostrarlo en la vista
        session([
            'codigo'    => $codigo,
            'respuesta' => $jsonData['analisis'] ?? $rawOutput,
            'metricas'  => [
                'lineas_analizadas' => $lineasAnalizadas, // ✅
                'funcionalidad'  => $jsonData['funcionalidad'] ?? 0,
                'eficiencia'     => $jsonData['eficiencia'] ?? 0,
                'compatibilidad' => $jsonData['compatibilidad'] ?? 0,
                'usabilidad'     => $jsonData['usabilidad'] ?? 0,
                'fiabilidad'     => $jsonData['fiabilidad'] ?? 0,
                'seguridad'      => $jsonData['seguridad'] ?? 0,
                'mantenibilidad' => $jsonData['mantenibilidad'] ?? 0,
                'portabilidad'   => $jsonData['portabilidad'] ?? 0,
            ],
        ]);

        // 🔹 Retornar a la vista con mensaje
        return redirect()
            ->route('code_analysis.index')
            ->with('swal', '¡Análisis guardado con éxito!')
            ->with('registro_id', $registro->id)
            ->with('respuesta', $jsonData['analisis'] ?? $rawOutput);

    } catch (\Exception $e) {
        return back()->withErrors([
            'error' => 'Error al conectar con CometAPI: ' . $e->getMessage(),
        ]);
    }
}

public function graficos()
{
    try {
        // Obtener el último registro analizado
        $ultimo = \App\Models\ProjectMetric::latest()->first();

        if (!$ultimo) {
            return response()->json([
                'success' => false,
                'error' => 'No hay análisis registrados aún.'
            ]);
        }

        // Preparar los datos para las cards
        $metricas = [
            //'lineas_analizadas' => $ultimo->lineas_analizadas ?? 0,
            'lineas_analizadas' => $ultimo->lineas_analizadas ?? 0,
            'funcionalidad'     => $ultimo->funcionalidad ?? 0,
            'eficiencia'        => $ultimo->eficiencia ?? 0,
            'compatibilidad'    => $ultimo->compatibilidad ?? 0,
            'usabilidad'        => $ultimo->usabilidad ?? 0,
            'fiabilidad'        => $ultimo->fiabilidad ?? 0,
            'seguridad'         => $ultimo->seguridad ?? 0,
            'mantenibilidad'    => $ultimo->mantenibilidad ?? 0,
            'portabilidad'      => $ultimo->portabilidad ?? 0,
        ];

        Log::info('Último registro', ['ultimo' => $ultimo]);
        // ✅ Ahora devolvemos también el ID
        return response()->json([
            'success'  => true,
            'id'       => $ultimo->id,
            'metricas' => $metricas,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error'   => 'Error al obtener métricas: ' . $e->getMessage(),
        ]);
    }
}


    /**
     * Genera y descarga el reporte PDF de la evaluación.
     */
    public function reporte()
    {
        $codigo = session('codigo');
        $respuesta = session('respuesta');

        // Evita el bucle de redirecciones
        if (!$codigo || !$respuesta) {
            return redirect()->route('evaluacion.index')
                ->withErrors(['error' => 'No hay datos disponibles para generar el reporte.']);
        }

        $data = [
            'titulo'    => 'Reporte de Evaluación ISO/IEC 25010',
            'codigo'    => $codigo,
            'respuesta' => $respuesta,
            'fecha'     => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('evaluacion.reporte', $data)
            ->setPaper('a4', 'portrait');

        $nombreArchivo = 'Reporte_Evaluacion_ISO25010_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($nombreArchivo);
    }

    public function generarMetricas(Request $request)
{
    $codigo = session('codigo'); // usa el último código evaluado
    if (!$codigo) {
        return response()->json([
            'error' => 'No hay código evaluado en la sesión. Realiza primero una evaluación.'
        ], 400);
    }

    try {
        $client = new \GuzzleHttp\Client([
            'base_uri' => env('COMET_API_URL', 'https://api.cometapi.com'),
        ]);

        $response = $client->post('/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('COMET_API_KEY'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => env('COMET_DEFAULT_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Eres **Qualisoft AI-Eval**, auditor técnico especializado en calidad de software ISO/IEC 25010. 
                        Analiza el siguiente código y devuelve únicamente un JSON válido con las métricas cuantitativas 
                        (porcentaje del 0 al 100) para los siguientes campos:
                        funcionalidad, mantenibilidad, eficiencia, usabilidad, compatibilidad, portabilidad, fiabilidad, seguridad.
                        No incluyas texto adicional, ni explicaciones, ni Markdown."
                    ],
                    [
                        'role' => 'user',
                        'content' => "Código a evaluar:\n\n{$codigo}"
                    ],
                ],
            ],
        ]);

        $result = json_decode($response->getBody(), true);
        $content = $result['choices'][0]['message']['content'] ?? '{}';

        $metricas = json_decode($content, true);

        if (!is_array($metricas)) {
            return response()->json([
                'error' => 'La IA no devolvió un JSON válido.',
                'raw' => $content
            ], 422);
        }

        // opcional: guardar métricas en sesión
        session(['metricas' => $metricas]);

        return response()->json([
            'success' => true,
            'metricas' => $metricas
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al conectar con CometAPI: ' . $e->getMessage(),
        ], 500);
    }
    }

    public function generarReporteAnalisis($id = null)
{
    // Si se pasa un ID, busca ese registro; si no, toma el más reciente
    $registro = \App\Models\ProjectMetric::when($id, function ($q) use ($id) {
        $q->where('id', $id);
    })->latest()->first();

    if (!$registro) {
        return back()->withErrors(['error' => 'No hay análisis registrados.']);
    }

    $data = [
        'codigo'        => $registro->codigo,
        'analisis'      => $registro->analisis,
        'resumen'       => $registro->resumen,
        'metricas'      => [
            'Líneas Analizadas' => $registro->lineas_analizadas,
            'Funcionalidad'     => $registro->funcionalidad,
            'Eficiencia'        => $registro->eficiencia,
            'Compatibilidad'    => $registro->compatibilidad,
            'Usabilidad'        => $registro->usabilidad,
            'Fiabilidad'        => $registro->fiabilidad,
            'Seguridad'         => $registro->seguridad,
            'Mantenibilidad'    => $registro->mantenibilidad,
            'Portabilidad'      => $registro->portabilidad,
        ],
    ];

    $pdf = PDF::loadView('evaluacion.reporte', $data)
              ->setPaper('a4', 'portrait');
    return $pdf->stream('reporte.pdf');

}

public function mostrarResultado($id)
{
    $registro = \App\Models\ProjectMetric::findOrFail($id);

    return view('evaluacion.resultado', compact('registro'));
}
}
