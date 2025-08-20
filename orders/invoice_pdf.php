<?php
// No HTML/echo before this file outputs PDF!
require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/fpdf.php";

if (!isset($_GET['id'])) {
  die("Invalid Request! No Order ID.");
}
$order_id = (int)$_GET['id'];

// Fetch order
$sql = "SELECT o.id, u.name, u.email, o.total, o.status, o.created_at
        FROM orders o
        JOIN users u ON u.id = o.user_id
        WHERE o.id = $order_id";
$order = mysqli_fetch_assoc(mysqli_query($conn, $sql));
if (!$order) { die("Order not found!"); }

// Fetch items
$sql_items = "SELECT m.name, oi.qty, oi.price
              FROM order_items oi
              JOIN menu m ON m.id = oi.menu_id
              WHERE oi.order_id = $order_id";
$items = mysqli_query($conn, $sql_items);

// Create PDF
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();

// (Optional) Logo
// $pdf->Image(__DIR__ . '/../assets/images/logo.png', 10, 10, 25);

$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,10,'Foodie RMS - Invoice',0,1,'C');
$pdf->Ln(2);

$pdf->SetFont('Arial','',12);
$pdf->Cell(100,6,'Invoice #: '.$order['id'],0,1);
$pdf->Cell(100,6,'Customer: '.$order['name'],0,1);
$pdf->Cell(100,6,'Email: '.$order['email'],0,1);
$pdf->Cell(100,6,'Status: '.ucfirst($order['status']),0,1);
$pdf->Cell(100,6,'Date: '.$order['created_at'],0,1);
$pdf->Ln(5);

// Table header
$pdf->SetFont('Arial','B',12);
$pdf->Cell(90,10,'Item',1,0,'L');
$pdf->Cell(25,10,'Qty',1,0,'C');
$pdf->Cell(35,10,'Price',1,0,'R');
$pdf->Cell(40,10,'Total',1,1,'R');

// Table rows
$pdf->SetFont('Arial','',12);
mysqli_data_seek($items, 0);
$grand = 0;
while($r = mysqli_fetch_assoc($items)){
  $line = $r['qty'] * $r['price'];
  $grand += $line;

  $pdf->Cell(90,8,$r['name'],1,0,'L');
  $pdf->Cell(25,8,$r['qty'],1,0,'C');
  $pdf->Cell(35,8,number_format($r['price'],2),1,0,'R');
  $pdf->Cell(40,8,number_format($line,2),1,1,'R');
}

// Grand total (DB total যদি আলাদা থাকে—display consistency)
$pdf->SetFont('Arial','B',12);
$pdf->Cell(150,10,'Grand Total',1,0,'R');
$pdf->Cell(40,10,number_format($order['total'],2).' BDT',1,1,'R');

$pdf->Ln(5);
$pdf->SetFont('Arial','I',10);
$pdf->Cell(190,6,'Thanks for your order!',0,1,'C');

// Output to browser
// কোনো echo/HTML নেই—সরাসরি PDF আউটপুট
$pdf->Output('I', 'invoice_'.$order['id'].'.pdf');
exit;
