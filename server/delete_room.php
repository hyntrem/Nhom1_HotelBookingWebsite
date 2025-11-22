<?php
// delete_room.php

require_once __DIR__ . '/../db_connect.php';
session_start();

$id = intval($_GET['id'] ?? 0);
if($id <= 0){
    echo "<script>alert('ID không hợp lệ');history.back();</script>";
    exit;
}

$stmt = $pdo->prepare("SELECT image FROM rooms WHERE id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);
if($r && !empty($r['image'])){
    $f = __DIR__ . '/../../client/' . $r['image'];
    if(file_exists($f)) @unlink($f);
}

$stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
$ok = $stmt->execute([$id]);

if($ok){
    echo "<script>alert('Xóa phòng thành công');window.location='../../client/admin_rooms.html';</script>";
    exit;
} else {
    echo "<script>alert('Lỗi khi xóa phòng');history.back();</script>";
    exit;
}
?>
