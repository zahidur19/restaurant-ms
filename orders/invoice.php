<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer'){
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . "/../includes/config.php";

// helper
function h($s){ return htmlspecialchars($s ?? "", ENT_QUOTES, 'UTF-8'); }

// ================= Order ID Check =================
if(!isset($_GET['order_id'])){
    die("❌ Order ID missing.");
}
$order_id = (int)$_GET['order_id'];
$user_id  = (int)$_SESSION['user']['id'];

// ================= Order Info =================
$stmt = $conn->prepare("SELECT id, total, status, created_at 
                        FROM orders 
                        WHERE id=? AND user_id=? LIMIT 1");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$order){
    die("❌ Invalid order or access denied.");
}

// ================= Order Items =================
$items = [];
$stmt = $conn->prepare("SELECT oi.qty, oi.price, m.name 
                        FROM order_items oi 
                        JOIN menu m ON oi.menu_id = m.id 
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$res = $stmt->get_result();
while($row = $res->fetch_assoc()){ $items[] = $row; }
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $order['id']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f9f9f9;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        table {
            width: 80%;
            margin: 15px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #eee;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h2>Restaurant Bill Invoice</h2>

    <table>
        <tr><th>Invoice Header</th><th>Value</th></tr>
        <tr><td>Order ID</td><td>#<?php echo (int)$order['id']; ?></td></tr>
        <tr><td>Date</td><td><?php echo h($order['created_at']); ?></td></tr>
        <tr><td>Customer</td><td><?php echo h($_SESSION['user']['name']); ?></td></tr>
        <tr><td>Status</td><td><?php echo ucfirst($order['status']); ?></td></tr>
    </table>

    <table>
        <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Price (৳)</th>
            <th>Total (৳)</th>
        </tr>
        <?php 
        $grandTotal = 0;
        foreach($items as $it): 
            $lineTotal = $it['qty'] * $it['price'];
            $grandTotal += $lineTotal;
        ?>
        <tr>
            <td><?php echo h($it['name']); ?></td>
            <td><?php echo (int)$it['qty']; ?></td>
            <td><?php echo number_format($it['price'], 2); ?></td>
            <td><?php echo number_format($lineTotal, 2); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="3">Grand Total</th>
            <th>৳ <?php echo number_format($grandTotal, 2); ?></th>
        </tr>
    </table>

    <div class="footer">
        Thank you for ordering with us! 🍽️
    </div>
</body>
</html>
