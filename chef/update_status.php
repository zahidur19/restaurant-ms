<?php
session_start();
include "../includes/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = intval($_POST['id']);
  $status = $_POST['status'];

  $sql = "UPDATE orders SET status=? WHERE id=?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "si", $status, $id);
  mysqli_stmt_execute($stmt);
}
header("Location: dashboard_chef.php");
exit;
