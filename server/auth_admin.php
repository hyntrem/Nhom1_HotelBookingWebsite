<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Bạn chưa đăng nhập"]);
    exit;
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    http_response_code(403);
    echo json_encode(["error" => "Bạn không có quyền truy cập (chỉ admin)"]);
    exit;
}
?>
