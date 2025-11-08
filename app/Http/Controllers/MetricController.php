<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectMetric;
use App\Models\Project;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MetricController extends Controller
{
    public function analyze(Project $project, Request $request)
    {
        $content = $request->input('content');

        if (empty(trim($content))) {
            return response()->json(['error' => 'Debe ingresar código o texto para analizar.'], 400);
        }

        try {
            // 📡 Llamada a CometAPI
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('COMET_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->timeout(60)->post(env('COMET_API_URL'), [
                'model' => env('COMET_DEFAULT_MODEL', 'gpt-4o-mini'),
                'messages' => [[
                    'role' => 'system',
                    'content' => "Eres un evaluador de software experto en ISO/IEC 25010 y 25023.
                    Analiza el siguiente código y devuelve ÚNICAMENTE un JSON con los siguientes campos numéricos (0 a 100) y un resumen textual:
                    {
                        'lineas_analizadas': <numero>,
                        'funcionalidad': <porcentaje>,
                        'eficiencia': <porcentaje>,
                        'compatibilidad': <porcentaje>,
                        'usabilidad': <porcentaje>,
                        'fiabilidad': <porcentaje>,
                        'seguridad': <porcentaje>,
                        'mantenibilidad': <porcentaje>,
                        'portabilidad': <porcentaje>,
                        'resumen': '<texto breve>'
                    }"
                ], [
                    'role' => 'user',
                    'content' => $content,
                ]]
            ]);

            $data = $response->json();

            if (!is_array($data)) {
                return response()->json(['error' => 'La IA devolvió una respuesta no válida.', 'raw' => $data], 500);
            }

            // 🧩 Guardar métricas
            $metric = ProjectMetric::updateOrCreate(
                ['project_id' => $project->id],
                $data
            );

            return response()->json(['success' => true, 'metrics' => $metric]);

        } catch (\Exception $e) {
            Log::error('Error al analizar métricas', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo conectar con la IA.'], 500);
        }
    }

    public function show(Project $project)
    {
        $metrics = ProjectMetric::where('project_id', $project->id)->first();
        return view('metrics.show', compact('metrics', 'project'));
    }
}
