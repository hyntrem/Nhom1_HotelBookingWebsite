<?php // register.php
session_start();
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/helpers.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
json_response(['error' => 'Method not allowed'], 405);
}


$input = json_input();
$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$role = ($input['role'] ?? 'user') === 'admin' ? 'admin' : 'user'; 


if (!$name || !$email || !$password) {
json_response(['error' => 'name, email and password are required'], 422);
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
json_response(['error' => 'invalid email format'], 422);
}


// check existing email
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
json_response(['error' => 'email already registered'], 409);
}


$password_hash = password_hash($password, PASSWORD_DEFAULT);


$stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
$stmt->execute([$name, $email, $password_hash, $role]);
$userId = $pdo->lastInsertId();


// auto-login after register 
$_SESSION['user'] = [
'id' => (int)$userId,
'name' => $name,
'email' => $email,
'role' => $role,
];


json_response(['message' => 'registered', 'user' => $_SESSION['user']], 201);
?>
