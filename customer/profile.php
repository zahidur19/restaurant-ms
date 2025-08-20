<?php
session_start();
include __DIR__."/../includes/config.php";

if(!isset($_SESSION['user'])) {
  header("Location: /restaurant-ms/auth/login.php");
  exit;
}

$user = $_SESSION['user'];

// অর্ডার ডাটা নিয়ে আসি
$sql = "SELECT o.id, o.total, o.status, o.created_at 
        FROM orders o 
        WHERE o.user_id = ".$user['id']." 
        ORDER BY o.created_at DESC";
$res = mysqli_query($conn, $sql);
$orders = [];
if($res) {
  while($row = mysqli_fetch_assoc($res)) {
    $orders[] = $row;
  }
}
?>
<?php include __DIR__."/../includes/header.php"; ?>


<div class="container my-4">
  <h2 class="mb-4">👤 My Profile</h2>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title">Personal Information</h5>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
      <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
    </div>
  </div>

  <h4 class="mb-3">📦 My Orders</h4>
  <?php if(count($orders) > 0): ?>
    <table class="table table-striped">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Total (৳)</th>
          <th>Status</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($orders as $o): ?>
          <tr>
            <td><?php echo $o['id']; ?></td>
            <td><?php echo number_format($o['total'],2); ?></td>
            <td>
              <span class="badge 
                <?php 
                  if($o['status']=='placed') echo 'bg-primary';
                  elseif($o['status']=='preparing') echo 'bg-warning text-dark';
                  elseif($o['status']=='ready') echo 'bg-info';
                  elseif($o['status']=='completed') echo 'bg-success';
                  else echo 'bg-danger';
                ?>">
                <?php echo ucfirst($o['status']); ?>
              </span>
            </td>
            <td><?php echo date("d M Y h:i A", strtotime($o['created_at'])); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">You have no orders yet.</div>
  <?php endif; ?>
</div>

<?php include __DIR__."/../includes/footer.php"; ?>
