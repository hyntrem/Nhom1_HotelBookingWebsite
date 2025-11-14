<?php // login.php

require_once '../db_connect.php';
session_start();

header('Content-Type: application/json');

// Đọc JSON input
$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(["error" => "Thiếu thông tin đăng nhập"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        echo json_encode(["success" => true, "role" => $user['role']]);
    } else {
        echo json_encode(["error" => "Sai email hoặc mật khẩu"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Lỗi kết nối máy chủ", "message" => $e->getMessage()]);
}
?>
