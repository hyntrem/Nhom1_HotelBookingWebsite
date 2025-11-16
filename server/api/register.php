<?php
// server/api/register.php

require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../helpers.php';

// Lấy JSON từ body
$input = json_input();

$email    = trim($input['email']    ?? '');
$password = trim($input['password'] ?? '');

// Ở frontend có gửi "username" (họ tên), nhưng DB không có cột riêng -> tạm bỏ qua
// $fullName = trim($input['username'] ?? '');

if ($email === '' || $password === '') {
    json_response([
        'status'  => 'error',
        'message' => 'Thiếu email hoặc mật khẩu'
    ], 400);
}

// kiểm tra email đã tồn tại (dùng cột username để lưu email)
$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
$stmt->execute([$email]);
$exists = $stmt->fetch();

if ($exists) {
    json_response([
        'status'  => 'error',
        'message' => 'Email đã tồn tại'
    ], 409);
}

// mã hóa mật khẩu
$hashed = password_hash($password, PASSWORD_BCRYPT);

// thêm user mới, role mặc định: user
$stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
$stmt->execute([$email, $hashed, 'user']);

if ($stmt->rowCount() > 0) {
    json_response([
        'status'  => 'success',
        'message' => 'Đăng ký thành công'
    ], 201);
}

json_response([
    'status'  => 'error',
    'message' => 'Lỗi tạo tài khoản'
], 500);
