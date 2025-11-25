<?php

require_once __DIR__ . '/db_connect.php';
session_start();

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("Invalid ID");
}

// Lấy hình để xóa
$stmt = $pdo->prepare("SELECT image FROM rooms WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if ($data && $data['image']) {
    $file = __DIR__ . "/" . $data['image'];
    if (file_exists($file)) unlink($file);
}

// Xóa phòng
$stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
$ok = $stmt->execute([$id]);

echo $ok ? "SUCCESS" : "FAILED";
?>
