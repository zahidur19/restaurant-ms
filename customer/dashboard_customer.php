<?php
require_once __DIR__ . "/../includes/auth_guard.php";
require_role("customer");
require_once __DIR__ . "/../includes/config.php";
include __DIR__ . "/../includes/header.php";

function h($s){ return htmlspecialchars($s ?? "", ENT_QUOTES, 'UTF-8'); }

$user_id = (int)$_SESSION["user"]["id"];

$orders = [];
$stmt = $conn->prepare("SELECT id, total, status, created_at FROM orders WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
while($row = $res->fetch_assoc()){ $orders[] = $row; }
$stmt->close();
?>

<div class="container mt-5">
  <h2>Welcome <?php echo h($_SESSION["user"]["name"]); ?> 😊</h2>

  <?php if(isset($_SESSION["msg"])): ?>
    <div class="alert alert-info mt-3"><?php echo h($_SESSION["msg"]); unset($_SESSION["msg"]); ?></div>
  <?php endif; ?>

  <h4 class="mt-4">My Orders</h4>

  <?php if(empty($orders)): ?>
    <div class="alert alert-secondary">No orders yet.</div>
  <?php else: ?>
    <?php foreach($orders as $o): ?>
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between flex-wrap">
            <div>
              <strong>Order #<?php echo (int)$o['id']; ?></strong><br>
              <small class="text-muted"><?php echo h($o['created_at']); ?></small>
            </div>
            <div class="text-end">
              <div class="fw-bold">Total: ৳ <?php echo h($o['total']); ?></div>
              <span class="badge 
                <?php
                  echo $o['status']==='placed'?'bg-secondary':
                       ($o['status']==='preparing'?'bg-warning':
                       ($o['status']==='ready'?'bg-info':
                       ($o['status']==='completed'?'bg-success':'bg-danger')));
                ?>">
                <?php echo ucfirst($o['status']); ?>
              </span>
            </div>
          </div>

          <hr>

          <strong>Items:</strong>
          <ul class="mb-2">
            <?php
              $it = $conn->prepare("
                SELECT oi.qty, oi.price, m.name 
                FROM order_items oi 
                JOIN menu m ON m.id = oi.menu_id 
                WHERE oi.order_id = ?");
              $it->bind_param("i", $o['id']);
              $it->execute();
              $rs = $it->get_result();
              while($row = $rs->fetch_assoc()):
            ?>
              <li><?php echo (int)$row['qty']; ?> × <?php echo h($row['name']); ?> (৳ <?php echo h($row['price']); ?>)</li>
            <?php endwhile; $it->close(); ?>
          </ul>

          <!-- <a class="btn btn-sm btn-outline-primary" href="/restaurant-ms/orders/invoice.php?order_id=<?php echo (int)$o['id']; ?>">Download Bill pdf</a> -->
           <a class="btn btn-sm btn-outline-primary" 
   href="/restaurant-ms/orders/invoice.php?order_id=<?php echo (int)$o['id']; ?>">
   See Your Bill Status
</a>

        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
