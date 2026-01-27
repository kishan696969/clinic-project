<?php
date_default_timezone_set('Asia/Kolkata');

$conn = mysqli_connect("localhost", "root", "", "doctor_db");

if (!$conn) {
    die("Database connection failed");
}
?>
