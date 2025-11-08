<?php
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

$apiKey = 'sk-aPyH3uxGlRYqW0b9AuI04xjTWLafuC1nYuOnC4ZMeAeLZctu';
$baseUrl = 'https://api.cometapi.com/v1/chat/completions';
$model = 'gpt-4o-mini'; // o el modelo que CometAPI use por defecto

$data = [
    'model' => $model,
    'messages' => [
        [
            'role' => 'system',
            'content' => 'Eres un asistente experto en calidad de software según ISO/IEC 25010.'
        ],
        [
            'role' => 'user',
            'content' => 'Explica brevemente qué evalúa la norma ISO/IEC 25010.'
        ],
    ],
    'temperature' => 0.7,
    'max_tokens' => 300
];

$client = new Client();

try {
    $response = $client->post($baseUrl, [
        'headers' => [
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ],
        'json' => $data,
        'timeout' => 30,
    ]);

    $result = json_decode($response->getBody(), true);
    echo "✅ RESPUESTA DE COMETAPI:\n";
    print_r($result);

} catch (\GuzzleHttp\Exception\ClientException $e) {
    echo "❌ Error HTTP: " . $e->getResponse()->getStatusCode() . "\n";
    echo $e->getResponse()->getBody()->getContents();
} catch (\Exception $e) {
    echo "❌ ERROR GENERAL: " . $e->getMessage() . PHP_EOL;
}
