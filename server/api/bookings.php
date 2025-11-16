<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// KẾT NỐI DATABASE
$conn = new mysqli("localhost", "root", "", "homestay_db");
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Kết nối thất bại"]);
    exit;
}

// NHẬN JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Không nhận được dữ liệu"]);
    exit;
}

// LẤY DỮ LIỆU TỪ FORM
$user_id        = $data["user_id"] ?? null;
$homestay_id    = $data["homestay_id"] ?? null;
$guest_name     = $data["guest_name"] ?? null;
$guest_phone    = $data["guest_phone"] ?? null;
$check_in       = $data["check_in_date"] ?? null;
$check_out      = $data["check_out_date"] ?? null;

// KIỂM TRA TRỐNG
if (!$user_id || !$homestay_id || !$guest_name || !$guest_phone || !$check_in || !$check_out) {
    echo json_encode(["status" => "error", "message" => "Vui lòng điền đầy đủ thông tin"]);
    exit;
}

// KIỂM TRA HOMESTAY CÓ TỒN TẠI & CHƯA BOOK
$sqlCheck = "SELECT * FROM homestay WHERE id = ? AND booked = 0";
$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("i", $homestay_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(["status" => "error", "message" => "Phòng đã được đặt hoặc không tồn tại!"]);
    exit;
}

// THÊM BOOKING VÀO BẢNG BOOKED_ROOM
$sqlInsert = "
    INSERT INTO booked_room 
    (guest_name, guest_phone, check_in_date, check_out_date, homestay_id, user_id)
    VALUES (?, ?, ?, ?, ?, ?)
";
$stmtInsert = $conn->prepare($sqlInsert);
$stmtInsert->bind_param("ssssii", $guest_name, $guest_phone, $check_in, $check_out, $homestay_id, $user_id);

if (!$stmtInsert->execute()) {
    echo json_encode(["status" => "error", "message" => "Không thể đặt phòng"]);
    exit;
}

// ĐÁNH DẤU PHÒNG ĐÃ ĐƯỢC BOOK
$sqlUpdate = "UPDATE homestay SET booked = 1 WHERE id = ?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->bind_param("i", $homestay_id);
$stmtUpdate->execute();

// TRẢ VỀ KẾT QUẢ
echo json_encode([
    "status" => "success",
    "message" => "Đặt phòng thành công!",
    "booking_id" => $stmtInsert->insert_id
]);

$conn->close();
?>
