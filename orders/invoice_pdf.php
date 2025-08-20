<?php
include "../includes/config.php";
require("../includes/fpdf.php"); // fpdf.php রাখতে হবে includes ফোল্ডারে

if (!isset($_GET['id'])) {
    die("Invalid Request! No Order ID.");
}

$order_id = intval($_GET['id']);

// Order Info
$sql = "SELECT o.id, u.name, u.email, o.total, o.status, o.created_at 
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE o.id=$order_id";
$order = mysqli_fetch_assoc(mysqli_query($conn, $sql));

if (!$order) {
    die("Order not found!");
}

// Order Items
$sql_items = "SELECT m.name, oi.qty, oi.price 
              FROM order_items oi
              JOIN menu m ON oi.menu_id = m.id
              WHERE oi.order_id=$order_id";
$res_items = mysqli_query($conn, $sql_items);

// ✅ FPDF Start
$pdf = new FPDF();
$pdf->AddPage();

// Title
$pdf->SetFont("Arial","B",16);
$pdf->Cell(190,10,"Invoice #".$order['id'],0,1,"C");
$pdf->Ln(5);

// Customer Info
$pdf->SetFont("Arial","",12);
$pdf->Cell(100,10,"Customer: ".$order['name'],0,1);
$pdf->Cell(100,10,"Email: ".$order['email'],0,1);
$pdf->Cell(100,10,"Date: ".$order['created_at'],0,1);
$pdf->Ln(5);

// Table Header
$pdf->SetFont("Arial","B",12);
$pdf->Cell(70,10,"Item",1);
$pdf->Cell(30,10,"Qty",1);
$pdf->Cell(40,10,"Price",1);
$pdf->Cell(50,10,"Total",1);
$pdf->Ln();

// Table Data
$pdf->SetFont("Arial","",12);
while($row = mysqli_fetch_assoc($res_items)) {
    $pdf->Cell(70,10,$row['name'],1);
    $pdf->Cell(30,10,$row['qty'],1);
    $pdf->Cell(40,10,$row['price'],1);
    $pdf->Cell(50,10,$row['qty'] * $row['price'],1);
    $pdf->Ln();
}

// Total
$pdf->SetFont("Arial","B",12);
$pdf->Cell(140,10,"Grand Total",1);
$pdf->Cell(50,10,$order['total']." ৳",1);

$pdf->Output("I", "invoice_".$order['id'].".pdf");
