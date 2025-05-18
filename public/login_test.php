<?php
// Simple script to test the login API directly

// Set headers
header('Content-Type: application/json');

// Define API endpoint
$loginUrl = 'http://localhost/tiffine/public/index.php/api/auth/login';

// Test data
$loginData = json_encode([
    'email' => 'anikasingh33@gmail.com',
    'password' => '123456'
]);

// Function to make API request
function makeLoginRequest($url, $data) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ]);
    
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

// Test login
$result = makeLoginRequest($loginUrl, $loginData);

// Output result
echo json_encode([
    'timestamp' => date('Y-m-d H:i:s'),
    'login_data' => json_decode($loginData),
    'result' => $result
], JSON_PRETTY_PRINT);
