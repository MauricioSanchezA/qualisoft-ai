<?php
require __DIR__ . '/vendor/autoload.php';

$apiKey = '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';
$model = 'gpt-3.5-turbo';

$data = [
    'model' => $model,
    'messages' => [
        ['role' => 'system', 'content' => 'Eres un asistente especializado en métricas de calidad de software.'],
        ['role' => 'user', 'content' => '¿Qué es la calidad del software según ISO?']
    ],
    'max_tokens' => 200
];

$client = new \GuzzleHttp\Client();

try {
    $response = $client->post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ],
        'json' => $data,
    ]);

    $result = json_decode($response->getBody(), true);
    echo "RESPUESTA DE OPENAI:\n";
    print_r($result);

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
