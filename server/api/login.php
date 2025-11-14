<?php // login.php

require_once 'db_connect.php';
session_start();


header('Content-Type: application/json');


$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';


$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


if ($user && password_verify($password, $user['password'])) {
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];


echo json_encode(["success" => true, "role" => $user['role']]);
} else {
echo json_encode(["error" => "Sai email hoặc mật khẩu"]);
}
?>
