<?php // search_rooms.php

require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/helpers.php';


$q = trim($_GET['q'] ?? '');
$type = trim($_GET['type'] ?? '');
$min_price = is_numeric($_GET['min_price'] ?? null) ? (float)$_GET['min_price'] : null;
$max_price = is_numeric($_GET['max_price'] ?? null) ? (float)$_GET['max_price'] : null;


$sql = 'SELECT id, room_number, type, price, description FROM rooms WHERE is_active = 1';
$params = [];


if ($q !== '') {
$sql .= ' AND (room_number LIKE ? OR type LIKE ? OR description LIKE ?)';
$like = "%{$q}%";
$params[] = $like;
$params[] = $like;
$params[] = $like;
}
if ($type !== '') {
$sql .= ' AND type = ?';
$params[] = $type;
}
if ($min_price !== null) {
$sql .= ' AND price >= ?';
$params[] = $min_price;
}
if ($max_price !== null) {
$sql .= ' AND price <= ?';
$params[] = $max_price;
}


$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rooms = $stmt->fetchAll();


json_response(['rooms' => $rooms]);
?>
