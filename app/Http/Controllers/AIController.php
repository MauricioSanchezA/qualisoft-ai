<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class AIController extends Controller
{
    public function analyze(Request $request)
    {
        // 🧠 Prompt del usuario o uno por defecto
        $prompt = $request->input('prompt', 'Realiza un análisis profesional de la calidad del software según la norma ISO/IEC 25010.');

        try {
            // 💬 Generación con GPT-4o-mini (modelo actual)
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Eres **Qualisoft AI-AuditManager**, un sistema de auditoría avanzada de calidad de software.  
                    Utiliza las normas ISO/IEC 25010 y ISO/IEC 25023 para supervisar, evaluar y emitir reportes de calidad del producto.  
                    Cuando el usuario te entregue datos o código, genera un análisis completo, incluyendo: contexto, criterios aplicados, métricas calculadas, y plan de acción.  
                    Estructura tu respuesta con: Resumen Ejecutivo, Análisis Detallado, Indicadores Clave, Plan de Mejora."
                    ],
                    [
                        'role' => 'user',
                        'content' => $request->input('prompt', 'Por favor, analiza el siguiente fragmento o aspecto de software conforme a ambas normas:' ) . "\n\n" . ($codigo ?? '')
                    ],
                ],
            ]);

            // ✅ Extraer la respuesta generada
            $text = $response['choices'][0]['message']['content'] ?? 'No se recibió ninguna respuesta del modelo.';

            return response()->json(['result' => $text]);

        } catch (\Exception $e) {
            // ⚠️ Captura errores en la comunicación con la API
            return response()->json([
                'error' => 'Error al comunicarse con la API de CometAPI.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

