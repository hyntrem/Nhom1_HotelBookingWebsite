<?php
// server/api/login.php

session_start();

require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../helpers.php';

$input = json_input();

$email    = trim($input['email']    ?? '');
$password = trim($input['password'] ?? '');

if ($email === '' || $password === '') {
    json_response([
        'status'  => 'error',
        'message' => 'Thiếu email hoặc mật khẩu'
    ], 400);
}

// lấy user theo email (lưu trong cột username)
$stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    json_response([
        'status'  => 'error',
        'message' => 'Sai email hoặc mật khẩu'
    ], 401);
}

// kiểm tra mật khẩu
if (!password_verify($password, $user['password'])) {
    json_response([
        'status'  => 'error',
        'message' => 'Sai email hoặc mật khẩu'
    ], 401);
}

// lưu session
$_SESSION['user_id']  = $user['id'];
$_SESSION['username'] = $user['username']; // chính là email
$_SESSION['role']     = $user['role'];

// trả về JSON cho frontend
json_response([
    'status'  => 'success',
    'message' => 'Đăng nhập thành công',
    'user'    => [
        'id'       => (int)$user['id'],
        'username' => $user['username'],
        'role'     => $user['role']
    ],
    'role' => $user['role']
]);
