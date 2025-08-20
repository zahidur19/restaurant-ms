<?php
session_start();
require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/functions.php";
requireLoginRole('customer');

$user_id  = (int)$_SESSION['user_id'];
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) { die("Invalid order id."); }

// Check ownership (order belongs to this user?)
$q = "SELECT id, total, status, created_at FROM orders WHERE id=? AND user_id=?";
$st = mysqli_prepare($conn, $q);
mysqli_stmt_bind_param($st, "ii", $order_id, $user_id);
mysqli_stmt_execute($st);
$order = mysqli_fetch_assoc(mysqli_stmt_get_result($st));
if (!$order) { die("Order not found or access denied."); }

// Fetch items
$q2 = "SELECT oi.menu_id, oi.qty, oi.price, m.name 
       FROM order_items oi 
       JOIN menu m ON m.id = oi.menu_id
       WHERE oi.order_id=?";
$st2 = mysqli_prepare($conn, $q2);
mysqli_stmt_bind_param($st2, "i", $order_id);
mysqli_stmt_execute($st2);
$items = mysqli_stmt_get_result($st2);

include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/navbar.php";
?>
<div class="container my-5">
  <h2>Order #<?= e($order_id) ?> Items</h2>
  <p><b>Status:</b> <?= e(ucfirst($order['status'])) ?> &nbsp; | &nbsp; <b>Date:</b> <?= e($order['created_at']) ?></p>

  <div class="table-responsive mt-3">
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>Food</th>
          <th>Qty</th>
          <th>Price (৳)</th>
          <th>Total (৳)</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $grand = 0;
        while($r = mysqli_fetch_assoc($items)):
          $line = (int)$r['qty'] * (float)$r['price'];
          $grand += $line;
        ?>
        <tr>
          <td><?= e($r['name']) ?></td>
          <td><?= (int)$r['qty'] ?></td>
          <td><?= number_format((float)$r['price'], 2) ?></td>
          <td><?= number_format($line, 2) ?></td>
        </tr>
        <?php endwhile; ?>
        <tr>
          <td colspan="3" class="text-end"><b>Grand Total</b></td>
          <td><b><?= number_format($grand, 2) ?> ৳</b></td>
        </tr>
      </tbody>
    </table>
  </div>

  <a class="btn btn-dark" href="/restaurant-ms/orders/invoice.php?id=<?= (int)$order_id ?>">Download Invoice (PDF)</a>
</div>
<?php include __DIR__ . "/../includes/footer.php"; ?>
