<?php
session_start();
include "../includes/config.php";

// শুধুমাত্র chef role allowed
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'chef') {
//   header("Location: /restaurant-ms/auth/login.php");
//   exit;
// }

$sql = "SELECT o.id, u.name as customer, o.status, o.created_at, SUM(oi.qty) as total_items
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN order_items oi ON o.id = oi.order_id
        GROUP BY o.id
        ORDER BY o.created_at DESC";
$res = mysqli_query($conn, $sql);
?>
<?php include "../includes/header.php"; ?>
<h2>Chef Dashboard</h2>
<table class="table table-bordered">
  <tr>
    <th>Order ID</th>
    <th>Customer</th>
    <th>Items</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
  <?php while($row = mysqli_fetch_assoc($res)): ?>
    <tr>
      <td>#<?php echo $row['id']; ?></td>
      <td><?php echo $row['customer']; ?></td>
      <td><?php echo $row['total_items']; ?></td>
      <td><?php echo $row['status']; ?></td>
      <td>
        <form method="post" action="update_status.php" class="d-flex">
          <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
          <select name="status" class="form-select form-select-sm">
            <option value="preparing">Preparing</option>
            <option value="ready">Ready</option>
          </select>
          <button type="submit" class="btn btn-sm btn-success ms-2">Update</button>
        </form>
      </td>
    </tr>
  <?php endwhile; ?>
</table>
<?php include "../includes/footer.php"; ?>
