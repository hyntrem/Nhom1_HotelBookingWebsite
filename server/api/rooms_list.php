<?php
// rooms_list.php

require_once __DIR__ . '/../db_connect.php';
header('Content-Type: application/json');
$stmt = $pdo->prepare('SELECT id, name, price, description, image FROM rooms ORDER BY id DESC');
$stmt->execute();
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
