<?php
// update_room.php

require_once __DIR__ . '/../db_connect.php';
session_start();

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo "<script>alert('Phải gọi bằng POST');history.back();</script>";
    exit;
}

$id = intval($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$price = trim($_POST['price'] ?? '0');
$description = trim($_POST['description'] ?? '');

if($id <= 0){
    echo "<script>alert('ID không hợp lệ');history.back();</script>";
    exit;
}

$stmt = $pdo->prepare("SELECT image FROM rooms WHERE id = ?");
$stmt->execute([$id]);
$old = $stmt->fetch(PDO::FETCH_ASSOC);
$imagePath = $old['image'] ?? '';

if(!empty($_FILES['image']['name'])){
    $uploadsDir = __DIR__ . '/../../client/uploads';
    if(!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
    $safe = time(). '_' . preg_replace('/[^A-Za-z0-9._-]/','_', basename($_FILES['image']['name']));
    $target = $uploadsDir . '/' . $safe;
    if(!move_uploaded_file($_FILES['image']['tmp_name'], $target)){
        echo "<script>alert('Lỗi upload ảnh mới');history.back();</script>";
        exit;
    }

    if(!empty($imagePath)){
        $oldPath = __DIR__ . '/../../client/' . $imagePath;
        if(file_exists($oldPath)) @unlink($oldPath);
    }
    $imagePath = 'uploads/' . $safe;
}

$sql = "UPDATE rooms SET name=:name, price=:price, description=:description, image=:image WHERE id=:id";
$stmt = $pdo->prepare($sql);
$ok = $stmt->execute([':name'=>$name, ':price'=>$price, ':description'=>$description, ':image'=>$imagePath, ':id'=>$id]);

if($ok){
    echo "<script>alert('Cập nhật phòng thành công');window.location='../../client/admin_rooms.html';</script>";
    exit;
} else {
    echo "<script>alert('Lỗi khi cập nhật phòng');history.back();</script>";
    exit;
}
?>
