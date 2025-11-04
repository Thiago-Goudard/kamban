<?php


header('Content-Type: application/json; charset=utf-8');


$apiKey = 'YOUR_API_KEY';

if (empty($_GET['location']) || empty($_GET['timestamp'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Parâmetros "location" e "timestamp" são obrigatórios. Ex: ?location=39.6034810,-119.6822510&timestamp=1733428634']);
    exit;
}

$location = $_GET['location'];
$timestamp = (int) $_GET['timestamp'];

$url = 'https://maps.googleapis.com/maps/api/timezone/json?location=' . urlencode($location) . '&timestamp=' . $timestamp . '&key=' . urlencode($apiKey);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$curlErr = curl_error($ch);
curl_close($ch);

if ($curlErr) {
    http_response_code(500);
    echo json_encode(['error' => 'cURL error: ' . $curlErr]);
    exit;
}

$data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    echo json_encode(['error' => 'Resposta inválida da API']);
    exit;
}


if (isset($data['status']) && $data['status'] === 'OK') {
    $localTimestamp = $timestamp + ($data['dstOffset'] ?? 0) + ($data['rawOffset'] ?? 0);
    $data['local_datetime'] = gmdate('Y-m-d H:i:s', $localTimestamp);
}

echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
