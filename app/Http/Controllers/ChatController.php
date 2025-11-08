<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ChatHistory;

class ChatController extends Controller
{
    public function index()
    {
        $sessionId = session()->getId();
        $history = ChatHistory::where('session_id', $sessionId)->get();

        return view('ai.chat', compact('history'));
    }

    public function send(Request $request)
    {
        $userMessage = trim($request->input('message'));

        if (empty($userMessage)) {
            return response()->json(['error' => 'El mensaje está vacío.'], 400);
        }

        try {
            // ✅ Usamos directamente la URL del .env (ya contiene /v1/chat/completions)
            $url = env('COMET_API_URL', 'https://api.cometapi.com/v1/chat/completions');

            $payload = [
                'model' => env('COMET_DEFAULT_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Eres **Qualisoft AI-Chat**, un asistente experto en calidad de software.  
                        Responde preguntas sobre normas ISO/IEC 25010 y 25023 con claridad, tono profesional y estructura si es útil.  
                        No hagas análisis técnico profundo a menos que el usuario lo pida expresamente."
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage
                    ],
                ],
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('COMET_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->timeout(30)->post($url, $payload);

            $data = $response->json();

            // ✅ Verificamos respuesta válida
            if (isset($data['choices'][0]['message']['content'])) {
                $aiReply = $data['choices'][0]['message']['content'];

                ChatHistory::create([
                    'session_id' => session()->getId(),
                    'user_message' => $userMessage,
                    'ai_reply' => $aiReply,
                ]);

                return response()->json(['reply' => $aiReply]);
            }

            Log::warning('Respuesta inesperada del modelo', ['response' => $data]);

            return response()->json([
                'error' => 'No se recibió respuesta válida del modelo.',
                'debug' => $data
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error en ChatController@send', ['message' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error al comunicarse con CometAPI.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
