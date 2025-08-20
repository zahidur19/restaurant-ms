<?php
require_once __DIR__ . "/../includes/auth_guard.php";
require_role("chef");
require_once __DIR__ . "/../includes/config.php";

$allowed = ['placed','preparing','ready','completed','cancelled'];

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['order_id'], $_POST['status'])) {
  $oid = (int)$_POST['order_id'];
  $st  = $_POST['status'];
  if(in_array($st, $allowed, true)) {
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $st, $oid);
    $stmt->execute();
    $stmt->close();
  }
  header("Location: /restaurant-ms/chef/orders.php");
  exit;
}

// All orders with customer name
$sql = "
  SELECT o.id, o.total, o.status, o.created_at, u.name AS customer
  FROM orders o
  JOIN users u ON u.id = o.user_id
  ORDER BY o.created_at DESC
";
$res = $conn->query($sql);

function h($s){ return htmlspecialchars($s ?? "", ENT_QUOTES, 'UTF-8'); }

include __DIR__."/../includes/header.php";
?>
<h3 class="mb-3">Orders (Chef)</h3>

<table class="table table-bordered align-middle">
  <thead>
    <tr>
      <th>Order #</th>
      <th>Customer</th>
      <th>Total</th>
      <th>Status</th>
      <th>Items</th>
      <th>Update</th>
    </tr>
  </thead>
  <tbody>
    <?php while($o = $res->fetch_assoc()): ?>
      <tr>
        <td>#<?php echo (int)$o['id']; ?><br><small class="text-muted"><?php echo h($o['created_at']); ?></small></td>
        <td><?php echo h($o['customer']); ?></td>
        <td>৳ <?php echo h($o['total']); ?></td>
        <td><span class="badge bg-secondary"><?php echo ucfirst($o['status']); ?></span></td>
        <td style="min-width:220px;">
          <ul class="mb-0">
            <?php
              $stmt = $conn->prepare("
                SELECT oi.qty, oi.price, m.name 
                FROM order_items oi 
                JOIN menu m ON m.id=oi.menu_id 
                WHERE oi.order_id=?");
              $stmt->bind_param("i", $o['id']);
              $stmt->execute();
              $its = $stmt->get_result();
              while($it=$its->fetch_assoc()):
            ?>
              <li><?php echo (int)$it['qty']; ?> × <?php echo h($it['name']); ?> (৳ <?php echo h($it['price']); ?>)</li>
            <?php endwhile; $stmt->close(); ?>
          </ul>
        </td>
        <td>
          <form method="post" class="d-flex gap-2">
            <input type="hidden" name="order_id" value="<?php echo (int)$o['id']; ?>">
            <select name="status" class="form-select">
              <?php foreach($allowed as $s): ?>
                <option value="<?php echo $s; ?>" <?php echo $s===$o['status']?'selected':''; ?>>
                  <?php echo ucfirst($s); ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button class="btn btn-primary">Save</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include __DIR__."/../includes/footer.php"; ?>
