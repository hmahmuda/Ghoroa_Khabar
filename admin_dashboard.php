<?php
include 'db.php';
session_start();

// Check if user is logged in and is admin
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    redirect('login.php');
}

$orders = [];
$stats = [];

// Get order statistics
$stats_query = "SELECT 
    COUNT(*) as total_orders,
    COUNT(CASE WHEN payment_status = 'pending' THEN 1 END) as pending_payments,
    COUNT(CASE WHEN order_status = 'pending' THEN 1 END) as pending_orders,
    SUM(CASE WHEN payment_status = 'completed' THEN total_price ELSE 0 END) as total_revenue
FROM orders";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Get recent orders with user and dish details
$orders_query = "SELECT o.*, u.name as customer_name, u.contact as customer_contact, 
                        d.name as dish_name, d.price as dish_price
                 FROM orders o 
                 JOIN users u ON o.user_id = u.id 
                 JOIN dishes d ON o.dish_id = d.id 
                 ORDER BY o.order_date DESC 
                 LIMIT 50";
$orders_result = $conn->query($orders_query);
$orders = $orders_result->fetch_all(MYSQLI_ASSOC);

// Handle order status updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = sanitize_input($_POST['new_status']);
    
    $valid_statuses = ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'];
    if (in_array($new_status, $valid_statuses)) {
        $update_stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
        if ($update_stmt) {
            $update_stmt->bind_param("si", $new_status, $order_id);
            $update_stmt->execute();
            $update_stmt->close();
            
            // Redirect to refresh the page
            header("Location: admin_dashboard.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Ghoroa Khabar</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #dee2e6;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #ff6b6b;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .orders-table th, .orders-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .orders-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-confirmed { background-color: #d1ecf1; color: #0c5460; }
        .status-preparing { background-color: #d4edda; color: #155724; }
        .status-ready { background-color: #cce5ff; color: #004085; }
        .status-delivered { background-color: #d1e7dd; color: #0f5132; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .payment-pending { background-color: #fff3cd; color: #856404; }
        .payment-completed { background-color: #d1e7dd; color: #0f5132; }
        .payment-failed { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<!-- Navbar -->
<section class="navbar">
    <div class="container">
        <div class="logo">
            <a href="home.html"><img src="images/logo.png" alt="Ghoroa Khabar" class="img-responsive"></a>
        </div>
        <div class="menu text-right">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="foods.php">Foods</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</section>

<!-- Dashboard Header -->
<section class="food-search text-center">
    <div class="container">
        <h2>Admin Dashboard</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
        
        <!-- Quick Actions -->
        <div style="margin: 20px 0; padding: 15px; background: rgba(255,255,255,0.9); border-radius: 8px;">
            <h3>Quick Actions</h3>
            <a href="admin_delivery.php" style="background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin-right: 10px;">🚚 Manage Deliveries</a>
            <a href="setup_delivery_system.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">⚙️ Setup Delivery System</a>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="food-menu">
    <div class="container">
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total_orders']; ?></div>
                <div>Total Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending_orders']; ?></div>
                <div>Pending Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending_payments']; ?></div>
                <div>Pending Payments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">৳<?php echo number_format($stats['total_revenue'], 2); ?></div>
                <div>Total Revenue</div>
            </div>
        </div>

        <h3>Recent Orders</h3>
        
        <?php if (!empty($orders)): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Dish</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td>
                                <?php echo htmlspecialchars($order['customer_name']); ?><br>
                                <small><?php echo htmlspecialchars($order['customer_contact']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($order['dish_name']); ?></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td>৳<?php echo number_format($order['total_price'], 2); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                    <?php echo ucfirst($order['order_status']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge payment-<?php echo $order['payment_status']; ?>">
                                    <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y H:i', strtotime($order['order_date'])); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="new_status" onchange="this.form.submit()" style="padding: 4px; border-radius: 4px;">
                                        <option value="">Update Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="preparing">Preparing</option>
                                        <option value="ready">Ready</option>
                                        <option value="delivered">Delivered</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; padding: 50px;">No orders found.</p>
        <?php endif; ?>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="home.html" style="background-color: #ff6b6b; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px;">Back to Home</a>
        </div>
    </div>
</section>

<!-- Footer -->
<section class="footer">
    <div class="container text-center">
        <p>All rights reserved. Designed By <a href="#">Mahmuda</a></p>
    </div>
</section>

</body>
</html> 