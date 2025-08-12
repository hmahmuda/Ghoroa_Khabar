<?php
include 'db.php';
session_start();

// Check if user is logged in and is admin
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['assign_delivery'])) {
        $order_id = (int)$_POST['order_id'];
        $delivery_person_id = (int)$_POST['delivery_person_id'];
        
        $stmt = $conn->prepare("INSERT INTO delivery_assignments (order_id, delivery_person_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $order_id, $delivery_person_id);
        
        if ($stmt->execute()) {
            $conn->query("UPDATE orders SET order_status = 'confirmed' WHERE id = $order_id");
            $message = "Delivery assigned successfully!";
        } else {
            $error = "Failed to assign delivery.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Management - Ghoroa Khabar</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .delivery-section { padding: 20px; background: #f9f9f9; margin: 20px 0; border-radius: 8px; }
        .order-card { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; border: 1px solid #ddd; }
        .btn { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; margin: 2px; }
        .btn-primary { background: #ff6b6b; color: white; }
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <section class="navbar">
        <div class="container">
            <div class="logo">
                <a href="admin_dashboard.php">Ghoroa Khabar - Admin</a>
            </div>
            <div class="menu text-right">
                <ul>
                    <li><a href="admin_dashboard.php">Dashboard</a></li>
                    <li><a href="admin_delivery.php">Delivery</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>

    <div class="container">
        <h1>🚚 Delivery Management System</h1>
        
        <?php if ($message): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="delivery-section">
            <h2>📊 Delivery Statistics</h2>
            <?php
            $pending_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'")->fetch_row()[0];
            $active_deliveries = $conn->query("SELECT COUNT(*) FROM delivery_assignments WHERE status IN ('assigned', 'picked_up', 'in_transit')")->fetch_row()[0];
            $available_drivers = $conn->query("SELECT COUNT(*) FROM delivery_persons WHERE is_available = 1")->fetch_row()[0];
            ?>
            <p><strong>Pending Orders:</strong> <?php echo $pending_orders; ?></p>
            <p><strong>Active Deliveries:</strong> <?php echo $active_deliveries; ?></p>
            <p><strong>Available Drivers:</strong> <?php echo $available_drivers; ?></p>
        </div>

        <!-- Assign Delivery -->
        <div class="delivery-section">
            <h2>📦 Assign Delivery Person</h2>
            
            <?php
            $pending_orders = $conn->query("
                SELECT o.*, u.name as customer_name, u.contact 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.order_status = 'pending' 
                AND o.id NOT IN (SELECT order_id FROM delivery_assignments)
                ORDER BY o.order_date DESC
            ");
            ?>
            
            <?php while ($order = $pending_orders->fetch_assoc()): ?>
                <div class="order-card">
                    <h4>Order #<?php echo $order['id']; ?></h4>
                    <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($order['contact']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['delivery_address']); ?></p>
                    <p><strong>Total:</strong> ৳<?php echo number_format($order['total_price'], 2); ?></p>
                    
                    <form method="POST" style="margin-top: 10px;">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        
                        <label>Select Delivery Person:</label>
                        <select name="delivery_person_id" required style="padding: 5px; margin: 5px;">
                            <option value="">Choose delivery person...</option>
                            <?php
                            $drivers = $conn->query("SELECT * FROM delivery_persons WHERE is_available = 1 ORDER BY rating DESC");
                            while ($driver = $drivers->fetch_assoc()):
                            ?>
                                <option value="<?php echo $driver['id']; ?>">
                                    <?php echo htmlspecialchars($driver['name']); ?> 
                                    (<?php echo $driver['vehicle_type']; ?> - Rating: <?php echo $driver['rating']; ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                        
                        <button type="submit" name="assign_delivery" class="btn btn-primary">Assign Delivery</button>
                    </form>
                </div>
            <?php endwhile; ?>
            
            <?php if ($pending_orders->num_rows == 0): ?>
                <p>No pending orders to assign.</p>
            <?php endif; ?>
        </div>

        <!-- Active Deliveries -->
        <div class="delivery-section">
            <h2>📍 Active Deliveries</h2>
            
            <?php
            $active_deliveries = $conn->query("
                SELECT da.*, o.delivery_address, u.name as customer_name, u.contact,
                       dp.name as driver_name, dp.phone as driver_phone
                FROM delivery_assignments da
                JOIN orders o ON da.order_id = o.id
                JOIN users u ON o.user_id = u.id
                JOIN delivery_persons dp ON da.delivery_person_id = dp.id
                WHERE da.status IN ('assigned', 'picked_up', 'in_transit')
                ORDER BY da.assigned_at DESC
            ");
            ?>
            
            <?php while ($delivery = $active_deliveries->fetch_assoc()): ?>
                <div class="order-card">
                    <h4>Delivery #<?php echo $delivery['id']; ?> - Order #<?php echo $delivery['order_id']; ?></h4>
                    <p><strong>Customer:</strong> <?php echo htmlspecialchars($delivery['customer_name']); ?> (<?php echo htmlspecialchars($delivery['contact']); ?>)</p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($delivery['delivery_address']); ?></p>
                    <p><strong>Driver:</strong> <?php echo htmlspecialchars($delivery['driver_name']); ?> (<?php echo htmlspecialchars($delivery['driver_phone']); ?>)</p>
                    <p><strong>Status:</strong> 
                        <span class="status-badge status-<?php echo $delivery['status']; ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $delivery['status'])); ?>
                        </span>
                    </p>
                    <p><strong>Assigned:</strong> <?php echo date('M d, Y H:i', strtotime($delivery['assigned_at'])); ?></p>
                </div>
            <?php endwhile; ?>
            
            <?php if ($active_deliveries->num_rows == 0): ?>
                <p>No active deliveries.</p>
            <?php endif; ?>
        </div>

        <!-- Delivery Areas -->
        <div class="delivery-section">
            <h2>🗺️ Delivery Areas</h2>
            
            <?php
            $areas = $conn->query("SELECT * FROM delivery_areas ORDER BY area_name");
            ?>
            
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Area Name</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Postcode</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Delivery Fee</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Est. Time</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($area = $areas->fetch_assoc()): ?>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($area['area_name']); ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($area['postcode']); ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;">৳<?php echo number_format($area['delivery_fee'], 2); ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $area['estimated_time']; ?> min</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <span class="status-badge <?php echo $area['is_active'] ? 'status-confirmed' : 'status-pending'; ?>">
                                    <?php echo $area['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html> 