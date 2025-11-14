<?php // auth.php 

session_start();
require_once __DIR__ . '/helpers.php';

function require_login() {
if (empty($_SESSION['user']) || empty($_SESSION['user']['id'])) {
json_response(['error' => 'authentication required'], 401);
}
return $_SESSION['user'];
}

function require_admin() {
$user = require_login();
if (($user['role'] ?? 'user') !== 'admin') {
json_response(['error' => 'admin required'], 403);
}
return $user;
}
?>
