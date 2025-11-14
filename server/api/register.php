<?php // register.php
require_once 'db_connect.php';
session_start();


header('Content-Type: application/json');


$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';


if (!$username || !$email || !$password) {
echo json_encode(["error" => "Thiếu dữ liệu"]); exit;
}


$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = "user"; // mặc định


$stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $hashed_password, $role);


if ($stmt->execute()) {
echo json_encode(["success" => true, "message" => "Đăng ký thành công"]);
} else {
echo json_encode(["error" => "Email đã tồn tại hoặc lỗi hệ thống"]);
}
?>
