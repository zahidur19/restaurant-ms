<?php
session_start();
require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/functions.php";
requireLoginRole('customer');

$user_id = (int)$_SESSION['user_id'];

$q = "SELECT id, total, status, created_at FROM orders WHERE user_id=? ORDER BY created_at DESC";
$st = mysqli_prepare($conn, $q);
mysqli_stmt_bind_param($st, "i", $user_id);
mysqli_stmt_execute($st);
$orders = mysqli_stmt_get_result($st);

include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/navbar.php";
?>
<div class="container my-5">
  <h2>My Orders</h2>

  <?php if(mysqli_num_rows($orders) === 0): ?>
    <div class="alert alert-info mt-3">You have no orders yet.</div>
  <?php else: ?>
  <div class="table-responsive mt-3">
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>Order #</th>
          <th>Total (৳)</th>
          <th>Status</th>
          <th>Date</th>
          <th>Details</th>
          <th>Invoice</th>
        </tr>
      </thead>
      <tbody>
      <?php while($o = mysqli_fetch_assoc($orders)): ?>
        <tr>
          <td>#<?= e($o['id']) ?></td>
          <td><?= number_format((float)$o['total'], 2) ?></td>
          <td>
            <?php
              $status = $o['status'];
              $badge = 'bg-secondary';
              if ($status === 'preparing') $badge = 'bg-warning';
              if ($status === 'ready')      $badge = 'bg-info';
              if ($status === 'completed')  $badge = 'bg-success';
              if ($status === 'cancelled')  $badge = 'bg-danger';
            ?>
            <span class="badge <?= $badge ?>"><?= e(ucfirst($status)) ?></span>
          </td>
          <td><?= e($o['created_at']) ?></td>
          <td><a class="btn btn-sm btn-outline-primary" href="/restaurant-ms/customer/order_items.php?id=<?= (int)$o['id'] ?>">View</a></td>
          <td><a class="btn btn-sm btn-outline-dark" href="/restaurant-ms/orders/invoice.php?id=<?= (int)$o['id'] ?>">Invoice</a></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
<?php include __DIR__ . "/../includes/footer.php"; ?>
