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
    <h2 class="mb-4">👨‍💼 Admin Dashboard</h2>

    <div class="row mb-4">

      <!-- Reports -->
<div class="col-md-3">
    <div class="card shadow-sm border-0">
        <div class="card-body text-center">
            <h5 class="card-title">📊 Reports</h5>
            <p class="card-text">View Sales & Analytics.</p>
            <a href="reports.php" class="btn btn-success btn-sm">Go</a>
        </div>
    </div>
</div>


        <!-- Manage Menu -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">🍔 Manage Menu</h5>
                    <p class="card-text">Add, update & delete food items.</p>
                    <!-- <a href="menu_manage.php" class="btn btn-primary btn-sm">Go</a> -->
                </div>
            </div>
        </div>

        <!-- Manage Orders -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">📦 Manage Orders</h5>
                    <p class="card-text">View & update all customer orders.</p>
                    <!-- <a href="orders_manage.php" class="btn btn-warning btn-sm">Go</a> -->
                </div>
            </div>
        </div>

        <!-- Manage Users
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">👥 Manage Users</h5>
                    <p class="card-text">View & manage customers & staff.</p>
                    <a href="users_manage.php" class="btn btn-info btn-sm">Go</a>
                </div>
            </div>
        </div>
    </div> -->

    


    <!-- ================= Reports Section ================= -->
    <h3 class="mt-5 mb-3">📊 Reports & Analytics</h3>
    <div class="row">
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
        $top_items = $conn->query("SELECT m.name, SUM(oi.qty) as total_qty 
                                   FROM order_items oi 
                                   JOIN menu m ON oi.menu_id=m.id 
                                   GROUP BY oi.menu_id 
                                   ORDER BY total_qty DESC LIMIT 5");

        // Orders by Status
        $status = $conn->query("SELECT status, COUNT(*) as total 
                                FROM orders 
                                GROUP BY status");
        ?>
        
        <!-- Total Sales -->
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5>Total Sales</h5>
                    <h3>৳ <?php echo $sales['total_sales'] ?? 0; ?></h3>
                </div>
            </div>
        </div>

        <!-- Sales Per Day -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>📅 Sales (Last 7 Days)</h5>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Sales (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $sales_per_day->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['order_date']; ?></td>
                                    <td><?php echo $row['daily_sales']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Items & Order Status -->
    <div class="row mt-4">
        <!-- Top Items -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>🥇 Top 5 Selling Items</h5>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty Sold</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $top_items->fetch_assoc()): ?>
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
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>📌 Orders by Status</h5>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Orders</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $status->fetch_assoc()): ?>
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

<?php include('../includes/footer.php'); ?>
