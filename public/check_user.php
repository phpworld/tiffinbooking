<?php
// Simple script to check if a user exists in the database

// Set headers
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'tiffine';
$username = 'root';
$password = '';
$email = 'anikasingh33@gmail.com';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Show all users
    $stmt = $db->query("SELECT id, name, email, password FROM users");
    $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if specific user exists
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'message' => 'Database query completed',
        'all_users' => $allUsers,
        'specific_user' => $user ? [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],
            'is_admin' => $user['is_admin'] ?? null
        ] : null
    ], JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo json_encode([
        'status' => false,
        'message' => 'Database error',
        'error' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
