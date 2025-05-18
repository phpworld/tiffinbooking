<?php
// Simple script to test API endpoints directly

// Set headers
header('Content-Type: application/json');

// Define API endpoints
$baseUrl = 'http://localhost/tiffine/public/index.php/api';
$endpoints = [
    'test' => $baseUrl . '/test',
    'debug' => $baseUrl . '/debug',
    'debug_login' => $baseUrl . '/debug/login',
    'login' => $baseUrl . '/auth/login'
];

// Test data
$loginData = json_encode([
    'email' => 'Anikasingh33@gmail.com',
    'password' => '123456'
]);

// Function to make API requests
function makeRequest($url, $method = 'GET', $data = null) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ]);
        }
    }

    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    $error = curl_error($ch);

    curl_close($ch);

    return [
        'response' => $response ? json_decode($response, true) : null,
        'info' => $info,
        'error' => $error
    ];
}

// Test endpoints
$results = [
    'test_get' => makeRequest($endpoints['test']),
    'debug_get' => makeRequest($endpoints['debug']),
    'debug_login_post' => makeRequest($endpoints['debug_login'], 'POST', $loginData),
    'login_post' => makeRequest($endpoints['login'], 'POST', $loginData)
];

// Output results
echo json_encode([
    'timestamp' => date('Y-m-d H:i:s'),
    'results' => $results
], JSON_PRETTY_PRINT);
