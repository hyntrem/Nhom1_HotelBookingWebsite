<?php
// register.php

require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../helpers.php';

$input = json_input();

$fullName       = trim($input['fullName'] ?? '');
$email          = trim($input['email'] ?? '');
$password       = trim($input['password'] ?? '');
$confirmPassword = trim($input['confirmPassword'] ?? '');

if ($fullName === '' || $email === '' || $password === '' || $confirmPassword === '') {
    json_response(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin'], 400);
}

if ($password !== $confirmPassword) {
    json_response(['status' => 'error', 'message' => 'Mật khẩu xác nhận không khớp'], 400);
}

if (strlen($password) < 6) {
    json_response(['status' => 'error', 'message' => 'Mật khẩu phải từ 6 ký tự trở lên'], 400);
}

// Kiểm tra email đã tồn tại chưa (cột username trong DB lưu email)
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    json_response(['status' => 'error', 'message' => 'Email này đã được sử dụng'], 409);
}

// Mã hóa mật khẩu
$hashed = password_hash($password, PASSWORD_BCRYPT);

// Tạo tài khoản mới – role mặc định là user
$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
$stmt->execute([$email, $hashed]);

if ($stmt->rowCount() > 0) {
    json_response([
        'status'  => 'success',
        'message' => 'Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.'
    ], 201);
}

json_response(['status' => 'error', 'message' => 'Lỗi tạo tài khoản, vui lòng thử lại'], 500);
