<?php // login.php
session_start();
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/helpers.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
json_response(['error' => 'Method not allowed'], 405);
}


$input = json_input();
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';


if (!$email || !$password) json_response(['error' => 'email and password required'], 422);


$stmt = $pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();


if (!$user || !password_verify($password, $user['password_hash'])) {
json_response(['error' => 'invalid credentials'], 401);
}


// success - set session
$_SESSION['user'] = [
'id' => (int)$user['id'],
'name' => $user['name'],
'email' => $user['email'],
'role' => $user['role']
];


json_response(['message' => 'logged_in', 'user' => $_SESSION['user']]);
?>
