<?php
require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../auth_admin.php';

// Lấy danh sách phòng đã đặt
$sql = "
    SELECT 
        b.id,
        b.guest_name,
        b.guest_phone,
        b.check_in_date,
        b.check_out_date,
        h.room_type,
        h.location,
        h.room_price,
        u.username AS booked_by
    FROM booked_room b
    JOIN homestay h ON h.id = b.homestay_id
    LEFT JOIN users u ON u.id = b.user_id
    ORDER BY b.id DESC
";

$stmt = $pdo->query($sql);
$bookings = $stmt->fetchAll();

json_response([
    "status" => "success",
    "bookings" => $bookings
]);
?>
