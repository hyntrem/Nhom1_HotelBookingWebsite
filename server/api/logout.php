<?php //logout.php
session_start();
require_once 'helpers.php';

$_SESSION = [];
session_destroy();

json_response(['message' => 'logged_out']);
?>

