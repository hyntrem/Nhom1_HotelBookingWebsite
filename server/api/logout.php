<?php
// server/api/logout.php

session_start();
require_once __DIR__ . '/../helpers.php';

// Xóa hết session
$_SESSION = [];
session_unset();
session_destroy();

json_response([
    'status'  => 'success',
    'message' => 'Đã đăng xuất'
]);
