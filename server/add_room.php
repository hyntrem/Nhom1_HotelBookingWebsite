<?php
require_once __DIR__ . '/db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("This endpoint only accepts POST requests");
}

$name        = trim($_POST['name'] ?? '');
$price       = trim($_POST['price'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($name === '' || $price === '') {
    die("Missing required fields");
}

$imagePath = null;

if (!empty($_FILES['image']['name'])) {
    $uploadDir = __DIR__ . "/uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $filename = time() . "_" . basename($_FILES['image']['name']);
    $target   = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $imagePath = "uploads/" . $filename;
    }
}

$sql = "INSERT INTO rooms (name, price, description, image) 
        VALUES (:name, :price, :description, :image)";
$stmt = $pdo->prepare($sql);

$ok = $stmt->execute([
    ":name" => $name,
    ":price" => $price,
    ":description" => $description,
    ":image" => $imagePath
]);

if ($ok) {
    echo "SUCCESS";
} else {
    echo "FAILED";
}
?>
