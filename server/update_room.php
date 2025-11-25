<?php
require_once __DIR__ . '/db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("This endpoint only accepts POST requests");
}

$id          = intval($_POST['id'] ?? 0);
$name        = trim($_POST['name'] ?? '');
$price       = trim($_POST['price'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($id <= 0 || $name === '' || $price === '') {
    die("Invalid input");
}

// Lấy hình cũ
$stmt = $pdo->prepare("SELECT image FROM rooms WHERE id = ?");
$stmt->execute([$id]);
$old = $stmt->fetch();

$imagePath = $old['image'];

// Upload hình mới nếu có
if (!empty($_FILES['image']['name'])) {

    // Xóa hình cũ
    if ($imagePath && file_exists(__DIR__ . "/" . $imagePath)) {
        unlink(__DIR__ . "/" . $imagePath);
    }

    // Upload hình mới
    $uploadDir = __DIR__ . "/uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $filename = time() . "_" . basename($_FILES['image']['name']);
    $target   = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $imagePath = "uploads/" . $filename;
    }
}

$sql = "UPDATE rooms 
        SET name=:name, price=:price, description=:description, image=:image 
        WHERE id=:id";

$stmt = $pdo->prepare($sql);

$ok = $stmt->execute([
    ":name" => $name,
    ":price" => $price,
    ":description" => $description,
    ":image" => $imagePath,
    ":id" => $id
]);

echo $ok ? "SUCCESS" : "FAILED";
?>
