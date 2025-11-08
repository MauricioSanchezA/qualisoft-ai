<?php

namespace App\Http\Controllers;

use App\Models\ProjectMetric;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class GraficosController extends Controller
{
    public function index()
    {
        return view('graficos');
    }

    public function generarMetricas(Request $request)
    {
        try {
            $client = new Client([
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
                            'content' => "Eres **Qualisoft AI-Eval**, un auditor técnico experto en calidad de software (ISO/IEC 25010). 
                            Analiza el código proporcionado y devuelve **solo un JSON válido** con los valores de las métricas.
                            No incluyas texto adicional, comentarios ni formato Markdown.
                            Campos esperados: funcionalidad, mantenibilidad, eficiencia, usabilidad, compatibilidad, portabilidad, fiabilidad, seguridad.
                            Cada valor debe ser un número (porcentaje del 0 al 100)."
                        ],
                        [
                            'role' => 'user',
                            'content' => "Analiza el siguiente código y genera las métricas solicitadas:\n\n" . $request->input('codigo', 'sin código de ejemplo'),
                        ],
                    ],
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            $content = $result['choices'][0]['message']['content'] ?? '{}';

            // Intentar decodificar el JSON generado por la IA
            $metricas = json_decode($content, true);

            if (!is_array($metricas)) {
                return response()->json([
                    'error' => 'La IA no devolvió un formato JSON válido.',
                    'raw' => $content,
                ], 422);
            }

            return response()->json([
                'success' => true,
                'metricas' => $metricas,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al conectar con CometAPI: ' . $e->getMessage(),
            ], 500);
        }
    }
}

