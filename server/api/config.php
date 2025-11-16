<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "homestay_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status"=>"error", "message"=>"Không kết nối DB"]));
}

$conn->set_charset("utf8mb4");
?>
