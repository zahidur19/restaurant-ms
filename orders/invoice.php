<?php
session_start();
require_once __DIR__ . "/../includes/config.php";

if (!isset($_GET['id'])) { die("Invalid Request!"); }
$order_id = (int)$_GET['id'];

// Order Info
$sql = "SELECT o.id, u.name, u.email, o.total, o.status, o.created_at
        FROM orders o
        JOIN users u ON u.id = o.user_id
        WHERE o.id = $order_id";
$order = mysqli_fetch_assoc(mysqli_query($conn, $sql));
if (!$order) { die("Order not found!"); }

// Order Items
$sql_items = "SELECT m.name, oi.qty, oi.price
              FROM order_items oi
              JOIN menu m ON m.id = oi.menu_id
              WHERE oi.order_id = $order_id";
$items = mysqli_query($conn, $sql_items);

include __DIR__ . "/../includes/header.php";
?>
<div class="container mt-4">
  <h2>Invoice #<?php echo $order['id']; ?></h2>
  <p><b>Customer:</b> <?php echo htmlspecialchars($order['name']); ?> (<?php echo htmlspecialchars($order['email']); ?>)</p>
  <p><b>Status:</b> <?php echo htmlspecialchars($order['status']); ?></p>
  <p><b>Date:</b> <?php echo htmlspecialchars($order['created_at']); ?></p>

  <table class="table table-bordered mt-3">
    <thead>
      <tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr>
    </thead>
    <tbody>
      <?php while($r = mysqli_fetch_assoc($items)): ?>
        <tr>
          <td><?php echo htmlspecialchars($r['name']); ?></td>
          <td><?php echo (int)$r['qty']; ?></td>
          <td><?php echo number_format($r['price'],2); ?></td>
          <td><?php echo number_format($r['qty']*$r['price'],2); ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <h4>Grand Total: ৳ <?php echo number_format($order['total'],2); ?></h4>

  <a class="btn btn-primary mt-3" href="invoice_pdf.php?id=<?php echo (int)$order['id']; ?>">Download PDF</a>
</div>
<?php include __DIR__ . "/../includes/footer.php"; ?>
