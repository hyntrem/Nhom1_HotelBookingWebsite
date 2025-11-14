<?php
session_start();
require_once 'db_connect.php';
require_once 'helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$input = json_input();
$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$role = 'user'; // tránh người dùng tự đặt admin

if (!$name || !$email || !$password) {
    json_response(['error' => 'name, email, password required'], 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response(['error' => 'invalid email format'], 422);
}

// check email
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) json_response(['error' => 'email exists'], 409);

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $email, $password_hash, $role]);
$userId = $pdo->lastInsertId();

// auto-login
$_SESSION['user'] = [
    'id' => (int)$userId,
    'name' => $name,
    'email' => $email,
    'role' => $role
];

json_response(['message' => 'registered', 'user' => $_SESSION['user']], 201);
?>

