<?php
// booking_stats.php

require_once __DIR__ . '/../db_connect.php';

$sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as period, COUNT(*) as total FROM bookings GROUP BY period ORDER BY period DESC LIMIT 6";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode(array_reverse($data));
?>
