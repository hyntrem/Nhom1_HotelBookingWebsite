<?php // register.php
require_once '../db_connect.php';
session_start();

header('Content-Type: application/json');

// Đọc JSON input
$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (!$username || !$email || !$password) {
    echo json_encode(["error" => "Thiếu dữ liệu"]);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = "user"; // mặc định

try {
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $hashed_password, $role]);
    
    echo json_encode(["success" => true, "message" => "Đăng ký thành công"]);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(["error" => "Email đã tồn tại"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Lỗi kết nối máy chủ", "message" => $e->getMessage()]);
    }
}
?>
