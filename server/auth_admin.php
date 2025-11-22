<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$username_to_display = $_SESSION['username'] ?? "Test Admin";
$is_authenticated = true;

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    $is_authenticated = false;
    $username_to_display = "Session Hết Hạn";
}

if ($is_authenticated && $_SESSION['role'] !== "admin") {
    $is_authenticated = false;
    $username_to_display = "Không Phải Admin";
}

echo json_encode([
    "status" => "success",
    "role"   => "admin",
    "username" => $username_to_display
]);

exit;
