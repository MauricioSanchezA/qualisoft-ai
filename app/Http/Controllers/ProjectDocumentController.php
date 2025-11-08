<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\ProjectCover;

class ProjectDocumentController extends Controller
{
    /**
     * Mostrar una sección específica del proyecto
     */
    public function show(Project $project, $sectionKey)
    {
        $doc = ProjectDocument::firstOrCreate([
            'project_id' => $project->id,
            'section_key' => $sectionKey,
        ]);

        return view('projects.sections.form', compact('project', 'doc', 'sectionKey'));
    }

        /**
     * 📄 Mostrar todas las secciones del proyecto en una vista general
     */
    public function index()
    {
        $documentos = \App\Models\ProjectDocument::orderBy('id')->get();
        return view('projects.documents.index', compact('documentos'));
    }

    /**
     * Guardar o actualizar el contenido del documento
     */
    public function update(Request $request, Project $project, $sectionKey)
    {
        $doc = ProjectDocument::firstOrCreate([
            'project_id' => $project->id,
            'section_key' => $sectionKey,
        ]);

        $doc->update([
            'content' => $request->input('content'),
            'status' => 'usuario_completo',
            'completed_at' => now(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('projects.documents.show', [$project, $sectionKey])
            ->with('success', '✅ Se ha guardado el apartado correctamente.');
    }

    /**
     * Analizar una sección del documento con IA
     */

public function analyzeByIAAjax(Project $project, $sectionKey, Request $request)
{
    $content = $request->input('content');

    if (empty(trim($content))) {
        return response()->json([
            'error' => 'Debe ingresar contenido antes de que la IA lo analice.'
        ], 400);
    }

    try {
        $client = new Client();

        // 🔹 Asignar nombre de sección legible para el prompt
        $sectionNames = [
            'problema' => 'Problema de investigación',
            'objetivos' => 'Objetivos del proyecto',
            'justificacion' => 'Justificación del estudio',
            'estado_arte' => 'Estado del arte o antecedentes',
            'marco_teorico' => 'Marco teórico',
            'marco_geografico' => 'Marco geográfico',
            'marco_normativo' => 'Marco normativo',
            'metodologia' => 'Metodología del estudio',
            'analisis' => 'Análisis de resultados',
            'conclusiones' => 'Conclusiones del proyecto',
            'recomendaciones' => 'Recomendaciones finales',
            'portada' => 'Portada del informe'
        ];

        $sectionName = $sectionNames[$sectionKey] ?? ucfirst(str_replace('_', ' ', $sectionKey));

        // 🔹 Prompt dinámico según la sección
        $systemPrompt = "Eres un auditor técnico experto en ISO/IEC 25010 y 25023, redacción académica y calidad del software.
Tu tarea es analizar y mejorar únicamente la sección titulada: **{$sectionName}**.

⚠️ Instrucciones estrictas:
- Devuelve **solo el texto corregido o mejorado** correspondiente a esa sección.
- No incluyas títulos de otras secciones, ni texto adicional fuera de la justificación.
- No agregues 'Introducción', 'Conclusión' o subtítulos, a menos que pertenezcan a esta sección.
- Mantén el mismo tono académico, la coherencia y la intención original.

Texto a analizar:
\"\"\"{$content}\"\"\"";

        // 🔹 Llamada a la API Comet (sin tocar lo que ya funciona)
        $response = $client->post(env('COMET_API_URL'), [
            'headers' => [
                'Authorization' => 'Bearer ' . env('COMET_API_KEY'),
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model'    => env('COMET_DEFAULT_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $content],
                ],
            ],
            'timeout' => 60,
        ]);

        // 🔹 Manejo de respuesta
        $bodyRaw = (string) $response->getBody();
        Log::info('Respuesta CometAPI RAW', ['body' => $bodyRaw]);

        // Verifica si viene HTML (error de conexión)
        if (str_starts_with(trim($bodyRaw), '<!DOCTYPE')) {
            Log::warning('CometAPI devolvió HTML (no JSON válido)', ['raw' => $bodyRaw]);
            return response()->json([
                'error' => 'La IA devolvió una respuesta no válida (HTML en lugar de JSON). Revisa la URL COMET_API_URL en tu .env.',
                'raw' => null
            ], 500);
        }

        $body = json_decode($bodyRaw, true);

        if (!isset($body['choices'][0]['message']['content'])) {
            Log::warning('Formato inesperado de respuesta CometAPI', ['decoded' => $body]);
            return response()->json([
                'error' => 'La IA devolvió una respuesta no válida.',
                'raw'   => $body
            ], 500);
        }

        $result = $body['choices'][0]['message']['content'];
        return response()->json(['result' => $result]);

    } catch (\Exception $e) {
        Log::error('Error al conectar con CometAPI', [
            'exception' => $e->getMessage(),
        ]);

        return response()->json([
            'error' => 'Error al conectar con CometAPI: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Descargar todas las secciones del proyecto en un solo PDF
     */
    public function downloadDocument(Project $project)
    {
        // 1️⃣ Traer todas las secciones ordenadas
        $sections = ProjectDocument::where('project_id', $project->id)
            ->orderByRaw("FIELD(section_key, 
                'portada','problema','objetivos','justificacion',
                'estado_arte','marco_teorico','marco_geografico',
                'marco_normativo','metodologia','analisis',
                'conclusiones','recomendaciones')")
            ->get();

        // 2️⃣ Pasar los datos a la vista del PDF
        $data = [
            'project'  => $project,
            'sections' => $sections,
            'fecha'    => now()->format('d/m/Y H:i'),
        ];

        // 3️⃣ Generar PDF con Barryvdh DomPDF
        $pdf = Pdf::loadView('projects.documents.pdf', $data)
            ->setPaper('a4')
            ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        // 4️⃣ Descargar
        return $pdf->download("Proyecto_{$project->id}.pdf");
    }

    /**
     * 📘 Exportar todo el proyecto a PDF con formato APA 7.0
     */
    public function exportarPDF()
{
    $portada = ProjectCover::first(); // o según el project_id
    $documentos = ProjectDocument::where('project_id', $portada->project_id ?? 1)->get();

    $pdf = PDF::loadView('projects.documents.pdf', compact('portada', 'documentos'))
        ->setPaper('letter', 'portrait'); // Hoja tamaño carta

    return $pdf->stream('informe_proyecto.pdf');
}

}
