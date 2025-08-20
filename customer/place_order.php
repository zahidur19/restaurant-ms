<?php
session_start();
require_once __DIR__ . "/../includes/config.php";

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "customer") {
  header("Location: /restaurant-ms/auth/login.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: /restaurant-ms/menu.php");
  exit;
}

$menu_id = isset($_POST["menu_id"]) ? (int)$_POST["menu_id"] : 0;
$qty     = isset($_POST["qty"]) ? max(1, (int)$_POST["qty"]) : 1;
$user_id = (int)$_SESSION["user"]["id"];

// 1) Fetch price from menu
$stmt = $conn->prepare("SELECT price FROM menu WHERE id=? AND status='active' LIMIT 1");
$stmt->bind_param("i", $menu_id);
$stmt->execute();
$res = $stmt->get_result();
$item = $res->fetch_assoc();
$stmt->close();

if (!$item) {
  $_SESSION["msg"] = "Invalid menu item or inactive.";
  header("Location: /restaurant-ms/menu.php");
  exit;
}

$price = (float)$item["price"];
$total = $price * $qty;

// 2) Transaction: create order + order_items
mysqli_begin_transaction($conn);
try {
  // orders row (status defaults to 'placed')
  $stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
  $stmt->bind_param("id", $user_id, $total);
  $stmt->execute();
  $order_id = $stmt->insert_id;
  $stmt->close();

  // order_items row
  $stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_id, qty, price) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("iiid", $order_id, $menu_id, $qty, $price);
  $stmt->execute();
  $stmt->close();

  mysqli_commit($conn);
  $_SESSION["msg"] = "✅ Order placed successfully (Order #$order_id).";
  header("Location: /restaurant-ms/customer/dashboard_customer.php");
  exit;
} catch (Exception $e) {
  mysqli_rollback($conn);
  $_SESSION["msg"] = "❌ Failed to place order.";
  header("Location: /restaurant-ms/menu.php");
  exit;
}
