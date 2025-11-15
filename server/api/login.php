<?php
header("Content-Type: application/json; charset=UTF-8");

$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
    exit;
}

$email = $input["email"] ?? "";
$password = $input["password"] ?? "";

// KẾT NỐI DATABASE
include "../config/dbconnect.php"; // giữ nguyên đúng file gốc của bạn

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(["status" => "error", "message" => "Email không tồn tại"]);
    exit;
}

// Kiểm tra mật khẩu plaintext (theo đúng DB dự án của bạn)
if ($password !== $user["password"]) {
    echo json_encode(["status" => "error", "message" => "Sai mật khẩu"]);
    exit;
}

// JSON trả về
echo json_encode([
    "status" => "success",
    "role" => $user["role"],
    "user" => [
        "id" => $user["id"],
        "name" => $user["username"],
        "email" => $user["email"]
    ]
]);
?>
