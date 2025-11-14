<?php // admin_list_bookings.php

require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';


$admin = require_admin();


$stmt = $pdo->query('SELECT b.id, b.user_id, u.name AS user_name, b.room_id, r.room_number, b.check_in, b.check_out, b.status, b.created_at
FROM bookings b
JOIN users u ON u.id = b.user_id
JOIN rooms r ON r.id = b.room_id
ORDER BY b.created_at DESC');
$bookings = $stmt->fetchAll();


json_response(['bookings' => $bookings]);
?>
