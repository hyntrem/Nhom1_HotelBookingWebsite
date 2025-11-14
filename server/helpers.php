<?php // helpers.php

function json_input() {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) return [];
    return $data;
}

function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function require_login() {
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        json_response(['error' => 'Bạn chưa đăng nhập'], 401);
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'role' => $_SESSION['role'] ?? 'user'
    ];
}
?>
