<?php
// add_room.php

require_once __DIR__ . '/../db_connect.php';
session_start();

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo "<script>alert('Phải gọi bằng POST');history.back();</script>";
    exit;
}

$name = trim($_POST['name'] ?? '');
$price = trim($_POST['price'] ?? '0');
$description = trim($_POST['description'] ?? '');

if($name === ''){
    echo "<script>alert('Tên phòng không được để trống');history.back();</script>";
    exit;
}

$imagePath = '';
if(!empty($_FILES['image']['name'])){
    $uploadsDir = __DIR__ . '/../../client/uploads';
    if(!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
    $safe = time(). '_' . preg_replace('/[^A-Za-z0-9._-]/','_', basename($_FILES['image']['name']));
    $target = $uploadsDir . '/' . $safe;
    if(!move_uploaded_file($_FILES['image']['tmp_name'], $target)){
        echo "<script>alert('Lỗi upload ảnh');history.back();</script>";
        exit;
    }
   
    $imagePath = 'uploads/' . $safe;
}

$sql = "INSERT INTO rooms (name, price, description, image) VALUES (:name, :price, :description, :image)";
$stmt = $pdo->prepare($sql);
$ok = $stmt->execute([':name'=>$name, ':price'=>$price, ':description'=>$description, ':image'=>$imagePath]);

if($ok){
    echo "<script>alert('Thêm phòng thành công');window.location='../../client/admin_rooms.html';</script>";
    exit;
} else {
    echo "<script>alert('Lỗi khi thêm phòng');history.back();</script>";
    exit;
}
?>
