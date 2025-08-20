<?php
session_start();
include "../includes/config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: /restaurant-ms/auth/login.php");
  exit;
}

$sql = "SELECT o.id, u.name as customer, o.total, o.status, o.created_at
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";
$res = mysqli_query($conn, $sql);
?>
<?php include "../includes/header.php"; ?>
<h2>All Orders</h2>
<table class="table table-bordered">
  <tr>
    <th>ID</th>
    <th>Customer</th>
    <th>Total</th>
    <th>Status</th>
    <th>Created At</th>
  </tr>
  <?php while($row = mysqli_fetch_assoc($res)): ?>
    <tr>
      <td>#<?php echo $row['id']; ?></td>
      <td><?php echo $row['customer']; ?></td>
      <td><?php echo $row['total']; ?> ৳</td>
      <td><?php echo $row['status']; ?></td>
      <td><?php echo $row['created_at']; ?></td>
    </tr>
  <?php endwhile; ?>
</table>
<?php include "../includes/footer.php"; ?>
