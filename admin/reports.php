<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    header("Location: ../auth/login.php");
    exit;
}
include('../includes/config.php');
include('../includes/header.php');
include('../includes/navbar.php');
?>

<div class="container mt-4">
    <h2 class="mb-4">📊 Reports & Analytics</h2>

    <?php
    // Total Sales
    $sales = $conn->query("SELECT SUM(total) as total_sales FROM orders WHERE status='completed'")->fetch_assoc();

    // Sales per Day (last 7 days)
    $sales_per_day = $conn->query("SELECT DATE(created_at) as order_date, SUM(total) as daily_sales 
                                   FROM orders 
                                   WHERE status='completed' 
                                   GROUP BY DATE(created_at) 
                                   ORDER BY order_date DESC LIMIT 7");

    // Top Items
    $top_items = $conn->query("SELECT f.name, SUM(o.quantity) as total_qty 
                               FROM orders o 
                               JOIN foods f ON o.food_id=f.id 
                               GROUP BY o.food_id 
                               ORDER BY total_qty DESC LIMIT 5");

    // Orders by Status
    $status = $conn->query("SELECT status, COUNT(*) as total 
                            FROM orders 
                            GROUP BY status");
    ?>

    <!-- Total Sales -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5>Total Sales</h5>
            <h3>৳ <?php echo $sales['total_sales'] ?? 0; ?></h3>
        </div>
    </div>

    <!-- Sales Per Day -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5>📅 Sales (Last 7 Days)</h5>
            <canvas id="salesChart" height="120"></canvas>
            <table class="table table-sm mt-3">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Sales (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sales_per_day->data_seek(0); 
                    while($row = $sales_per_day->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['order_date']; ?></td>
                            <td><?php echo $row['daily_sales']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Items & Status -->
    <div class="row">
        <!-- Top Items -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5>🥇 Top 5 Selling Items</h5>
                    <canvas id="itemsChart" height="200"></canvas>
                    <table class="table table-sm mt-3">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty Sold</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $top_items->data_seek(0);
                            while($row = $top_items->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['total_qty']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Orders by Status -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5>📌 Orders by Status</h5>
                    <canvas id="statusChart" height="200"></canvas>
                    <table class="table table-sm mt-3">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Orders</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $status->data_seek(0);
                            while($row = $status->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo ucfirst($row['status']); ?></td>
                                    <td><?php echo $row['total']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Sales Per Day Chart
const salesCtx = document.getElementById('salesChart');
new Chart(salesCtx, {
    type: 'bar',
    data: {
        labels: [<?php 
            $sales_per_day->data_seek(0);
            while($row = $sales_per_day->fetch_assoc()){ echo "'".$row['order_date']."',"; } 
        ?>],
        datasets: [{
            label: 'Daily Sales (৳)',
            data: [<?php 
            $sales_per_day->data_seek(0);
            while($row = $sales_per_day->fetch_assoc()){ echo $row['daily_sales'].","; } 
        ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

// Top Items Chart
const itemsCtx = document.getElementById('itemsChart');
new Chart(itemsCtx, {
    type: 'doughnut',
    data: {
        labels: [<?php 
            $top_items->data_seek(0);
            while($row = $top_items->fetch_assoc()){ echo "'".$row['name']."',"; } 
        ?>],
        datasets: [{
            data: [<?php 
            $top_items->data_seek(0);
            while($row = $top_items->fetch_assoc()){ echo $row['total_qty'].","; } 
        ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)'
            ]
        }]
    },
    options: { responsive: true }
});

// Orders by Status Chart
const statusCtx = document.getElementById('statusChart');
new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: [<?php 
            $status->data_seek(0);
            while($row = $status->fetch_assoc()){ echo "'".ucfirst($row['status'])."',"; } 
        ?>],
        datasets: [{
            data: [<?php 
            $status->data_seek(0);
            while($row = $status->fetch_assoc()){ echo $row['total'].","; } 
        ?>],
            backgroundColor: [
                'rgba(255, 159, 64, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(255, 99, 132, 0.7)'
            ]
        }]
    },
    options: { responsive: true }
});
</script>

<?php include('../includes/footer.php'); ?>
