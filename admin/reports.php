<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    header("Location: ../auth/login.php");
    exit;
}
include('../includes/config.php');
include('../includes/header.php');

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
                                   ORDER BY order_date ASC LIMIT 7");

    $sales_dates = [];
    $sales_amounts = [];
    while($row = $sales_per_day->fetch_assoc()){
        $sales_dates[] = $row['order_date'];
        $sales_amounts[] = $row['daily_sales'];
    }

    // Orders by Status
    $status = $conn->query("SELECT status, COUNT(*) as total 
                            FROM orders 
                            GROUP BY status");

    $status_labels = [];
    $status_counts = [];
    while($row = $status->fetch_assoc()){
        $status_labels[] = ucfirst($row['status']);
        $status_counts[] = $row['total'];
    }
    ?>

    <!-- Total Sales -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body text-center">
            <h5>Total Sales</h5>
            <h3>৳ <?php echo $sales['total_sales'] ?? 0; ?></h3>
        </div>
    </div>

    <div class="row">
        <!-- Sales Chart -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5>📅 Sales (Last 7 Days)</h5>
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Orders by Status Chart -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5>📌 Orders by Status</h5>
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales Chart
const ctx1 = document.getElementById('salesChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($sales_dates); ?>,
        datasets: [{
            label: 'Daily Sales (৳)',
            data: <?php echo json_encode($sales_amounts); ?>,
            borderColor: 'blue',
            backgroundColor: 'rgba(0,123,255,0.2)',
            fill: true,
            tension: 0.3
        }]
    }
});

// Orders by Status Chart
const ctx2 = document.getElementById('statusChart').getContext('2d');
new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($status_labels); ?>,
        datasets: [{
            data: <?php echo json_encode($status_counts); ?>,
            backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1']
        }]
    }
});
</script>

<?php include('../includes/footer.php'); ?>
