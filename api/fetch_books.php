<?php
header('Content-Type: application/json');

function send_json($payload, $statusCode = 200)
{
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

if (!isset($_GET['query']) || trim($_GET['query']) === '') {
    send_json(['error' => 'Missing query.'], 400);
}

$query = urlencode(trim($_GET['query']));
$url = "https://www.googleapis.com/books/v1/volumes?q={$query}";
$response = false;

if (function_exists('curl_init')) {
    $attempts = 2;
    for ($i = 0; $i < $attempts; $i++) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $result = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_errno($ch);
        curl_close($ch);

        if ($curlError === 0 && $httpCode >= 200 && $httpCode < 300 && $result !== false) {
            $response = $result;
            break;
        }
    }
} else {
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 10,
        ]
    ]);
    $response = @file_get_contents($url, false, $context);
}

if ($response === false) {
    send_json([
        'items' => [],
        'error' => 'Unable to reach book service. Please try again.'
    ], 503);
}

$decoded = json_decode($response, true);
if (!is_array($decoded)) {
    send_json([
        'items' => [],
        'error' => 'Invalid response from book service. Please try again.'
    ], 502);
}

echo json_encode($decoded);
?>
