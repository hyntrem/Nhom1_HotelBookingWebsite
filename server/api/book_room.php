<?php // book_room.php

require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';


$user = require_login();


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
json_response(['error' => 'Method not allowed'], 405);
}


$input = json_input();
$room_id = (int)($input['room_id'] ?? 0);
$check_in = $input['check_in'] ?? null;
$check_out = $input['check_out'] ?? null;


if (!$room_id || !$check_in || !$check_out) {
json_response(['error' => 'room_id, check_in and check_out are required'], 422);
}


// basic date validation
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $check_in) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $check_out)) {
json_response(['error' => 'dates must be in YYYY-MM-DD format'], 422);
}


if ($check_in >= $check_out) json_response(['error' => 'check_out must be after check_in'], 422);


// check room exists and is active
$stmt = $pdo->prepare('SELECT id FROM rooms WHERE id = ? AND is_active = 1');
$stmt->execute([$room_id]);
if (!$stmt->fetch()) json_response(['error' => 'room not found'], 404);


// check overlapping bookings
$stmt = $pdo->prepare('SELECT COUNT(*) AS cnt FROM bookings WHERE room_id = ? AND status != "cancelled" AND (NOT (check_out <= ? OR check_in >= ?))');
$stmt->execute([$room_id, $check_in, $check_out]);
$count = (int)$stmt->fetchColumn();
if ($count > 0) json_response(['error' => 'room not available for selected dates'], 409);


// create booking
$stmt = $pdo->prepare('INSERT INTO bookings (user_id, room_id, check_in, check_out, status) VALUES (?, ?, ?, ?, "pending")');
$stmt->execute([$user['id'], $room_id, $check_in, $check_out]);
$bookingId = $pdo->lastInsertId();


json_response(['message' => 'booking_created', 'booking_id' => (int)$bookingId], 201);
?>
