<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\Services;

/**
 * Generate a new JWT token
 *
 * @param array $userData User data to be encoded in the token
 * @return string The JWT token
 */
function generateJWTToken($userData)
{
    $key = getenv('JWT_SECRET_KEY');
    $issuedAt = time();
    $expirationTime = $issuedAt + 86400; // Valid for 24 hours
    
    $payload = [
        'iss' => 'Tiffine App',
        'aud' => 'Mobile App',
        'iat' => $issuedAt,
        'exp' => $expirationTime,
        'data' => $userData
    ];
    
    return JWT::encode($payload, $key, 'HS256');
}

/**
 * Validate JWT token
 *
 * @param string $token The JWT token to validate
 * @return object|false The decoded token data or false on failure
 */
function validateJWTToken($token)
{
    try {
        $key = getenv('JWT_SECRET_KEY');
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Get user data from JWT token
 *
 * @return array|null User data or null if token is invalid
 */
function getUserFromToken()
{
    $request = Services::request();
    $authHeader = $request->getHeaderLine('Authorization');
    
    if (empty($authHeader)) {
        return null;
    }
    
    // Extract token from Bearer header
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
        $decoded = validateJWTToken($token);
        
        if ($decoded) {
            return (array) $decoded->data;
        }
    }
    
    return null;
}
