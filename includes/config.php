<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "restaurant_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Database connection failed: " . mysqli_connect_error());
}

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
