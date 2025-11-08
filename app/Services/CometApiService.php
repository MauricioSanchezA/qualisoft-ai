<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CometApiService
{
    protected $apiKey;
    protected $baseUrl;
    protected $model;

    public function __construct()
    {
        $this->apiKey = env('COMET_API_KEY');
        $this->baseUrl = rtrim(env('COMET_API_BASE_URL', 'https://api.cometapi.com'), '/');
        $this->model = env('COMET_DEFAULT_MODEL', 'gpt-4o-mini');
    }

    public function sendMessage($message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un asistente especializado en métricas de calidad de software basadas en ISO/IEC 25010.'],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            if ($response->failed()) {
                Log::error('CometAPI error: ' . $response->body());
                return 'Error al comunicarse con CometAPI.';
            }

            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? 'No se recibió respuesta de la IA.';

        } catch (\Exception $e) {
            Log::error('Error CometApiService: ' . $e->getMessage());
            return 'Error al conectar con el servicio de IA.';
        }
    }
}
