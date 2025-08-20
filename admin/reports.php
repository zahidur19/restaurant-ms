<?php
include('../includes/config.php');
include('../includes/header.php');

// session_start();

// Admin role guard
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
  header("Location: ../auth/login.php");
  exit;
}

// Sales per day
$salesQuery = "SELECT DATE(created_at) as order_date, SUM(total) as total_sales 
               FROM orders 
               WHERE status IN ('completed','ready') 
               GROUP BY DATE(created_at) 
               ORDER BY order_date DESC 
               LIMIT 7";
$salesResult = $conn->query($salesQuery);

$salesData = [];
while($row = $salesResult->fetch_assoc()){
    $salesData['dates'][] = $row['order_date'];
    $salesData['totals'][] = $row['total_sales'];
}

// Top items
$topItemsQuery = "SELECT m.name, SUM(oi.qty) as qty 
                  FROM order_items oi
                  JOIN menu m ON oi.menu_id = m.id
                  GROUP BY m.id
                  ORDER BY qty DESC
                  LIMIT 5";
$topItemsResult = $conn->query($topItemsQuery);

$topItemsData = [];
while($row = $topItemsResult->fetch_assoc()){
    $topItemsData['items'][] = $row['name'];
    $topItemsData['qty'][] = $row['qty'];
}

// Order status distribution
$statusQuery = "SELECT status, COUNT(*) as count 
                FROM orders 
                GROUP BY status";
$statusResult = $conn->query($statusQuery);

$statusData = [];
while($row = $statusResult->fetch_assoc()){
    $statusData['status'][] = $row['status'];
    $statusData['count'][] = $row['count'];
}
?>

<div class="container mt-4">
  <h2 class="mb-4">📊 Admin Reports & Analytics</h2>

  <!-- Sales per day -->
  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title">Sales (Last 7 Days)</h5>
      <canvas id="salesChart"></canvas>
    </div>
  </div>

  <!-- Top Items -->
  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title">Top 5 Menu Items</h5>
      <canvas id="topItemsChart"></canvas>
    </div>
  </div>

  <!-- Order Status -->
  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title">Orders by Status</h5>
      <canvas id="statusChart"></canvas>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Sales chart
  const salesCtx = document.getElementById('salesChart').getContext('2d');
  new Chart(salesCtx, {
      type: 'line',
      data: {
          labels: <?php echo json_encode($salesData['dates']); ?>,
          datasets: [{
              label: 'Sales (৳)',
              data: <?php echo json_encode($salesData['totals']); ?>,
              borderColor: 'blue',
              fill: false,
              tension: 0.2
          }]
      }
  });

  // Top items chart
  const topItemsCtx = document.getElementById('topItemsChart').getContext('2d');
  new Chart(topItemsCtx, {
      type: 'bar',
      data: {
          labels: <?php echo json_encode($topItemsData['items']); ?>,
          datasets: [{
              label: 'Quantity Sold',
              data: <?php echo json_encode($topItemsData['qty']); ?>,
              backgroundColor: 'green'
          }]
      }
  });

  // Status chart
  const statusCtx = document.getElementById('statusChart').getContext('2d');
  new Chart(statusCtx, {
      type: 'pie',
      data: {
          labels: <?php echo json_encode($statusData['status']); ?>,
          datasets: [{
              data: <?php echo json_encode($statusData['count']); ?>,
              backgroundColor: ['orange', 'blue', 'green', 'red', 'gray']
          }]
      }
  });
</script>
