<?php
session_start();
include "../includes/config.php";

if (!isset($_GET['id'])) {
    die("Invalid Request!");
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

include "../includes/header.php";
?>

<h2>Invoice #<?php echo $order['id']; ?></h2>
<p><b>Customer:</b> <?php echo $order['name']; ?> (<?php echo $order['email']; ?>)</p>
<p><b>Status:</b> <?php echo $order['status']; ?></p>
<p><b>Date:</b> <?php echo $order['created_at']; ?></p>

<table class="table table-bordered">
  <tr>
    <th>Item</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Total</th>
  </tr>
  <?php while($row = mysqli_fetch_assoc($res_items)): ?>
    <tr>
      <td><?php echo $row['name']; ?></td>
      <td><?php echo $row['qty']; ?></td>
      <td><?php echo $row['price']; ?></td>
      <td><?php echo $row['qty'] * $row['price']; ?></td>
    </tr>
  <?php endwhile; ?>
</table>

<h3>Grand Total: <?php echo $order['total']; ?> ৳</h3>

<a href="invoice_pdf.php?id=<?php echo $order['id']; ?>" class="btn btn-primary">Download PDF</a>

<?php include "../includes/footer.php"; ?>
