<?php // auth.php
session_start();
require_once 'helpers.php';

function require_login() {
    if (empty($_SESSION['user'])) {
        json_response(['error' => 'authentication required'], 401);
    }
    return $_SESSION['user'];
}

function require_admin() {
    $user = require_login();
    if ($user['role'] !== 'admin') {
        json_response(['error' => 'admin required'], 403);
    }
    return $user;
}
?>
