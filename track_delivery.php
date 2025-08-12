<?php
include 'db.php';
session_start();

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$tracking_info = null;
$error = '';

if ($order_id > 0) {
    // Get delivery tracking information
    $stmt = $conn->prepare("
        SELECT da.*, o.delivery_address, o.total_price, u.name as customer_name,
               dp.name as driver_name, dp.phone as driver_phone, dp.vehicle_type, dp.vehicle_number
        FROM delivery_assignments da
        JOIN orders o ON da.order_id = o.id
        JOIN users u ON o.user_id = u.id
        JOIN delivery_persons dp ON da.delivery_person_id = dp.id
        WHERE da.order_id = ?
    ");
    
    if ($stmt) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $tracking_info = $result->fetch_assoc();
        } else {
            $error = "No delivery information found for this order.";
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
    <title>Track Delivery - Ghoroa Khabar</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .tracking-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .tracking-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .tracking-status {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
            position: relative;
        }
        
        .status-step {
            text-align: center;
            flex: 1;
            position: relative;
        }
        
        .status-step::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            width: 30px;
            height: 30px;
            background: #ddd;
            border-radius: 50%;
            transform: translateX(-50%);
            z-index: 2;
        }
        
        .status-step.active::before {
            background: #ff6b6b;
        }
        
        .status-step.completed::before {
            background: #28a745;
        }
        
        .status-step::after {
            content: '';
            position: absolute;
            top: 35px;
            left: 50%;
            width: 2px;
            height: 40px;
            background: #ddd;
            transform: translateX(-50%);
        }
        
        .status-step:last-child::after {
            display: none;
        }
        
        .status-step.completed::after {
            background: #28a745;
        }
        
        .status-label {
            margin-top: 60px;
            font-weight: bold;
            color: #333;
        }
        
        .status-step.active .status-label {
            color: #ff6b6b;
        }
        
        .status-step.completed .status-label {
            color: #28a745;
        }
        
        .delivery-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .driver-info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .estimated-time {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            text-align: center;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #ff6b6b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        
        .btn:hover {
            background: #ff5252;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <section class="navbar">
        <div class="container">
            <div class="logo">
                <a href="home.html">Ghoroa Khabar</a>
            </div>
            <div class="menu text-right">
                <ul>
                    <li><a href="home.html">Home</a></li>
                    <li><a href="categories.html">Categories</a></li>
                    <li><a href="foods.php">Foods</a></li>
                    <?php if (is_logged_in()): ?>
                        <li><a href="logout.php">Log Out</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Log In</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>

    <div class="tracking-container">
        <div class="tracking-header">
            <h1>🚚 Track Your Delivery</h1>
            <p>Order #<?php echo $order_id; ?></p>
        </div>

        <?php if ($error): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <?php echo $error; ?>
            </div>
            <div style="text-align: center; margin: 20px 0;">
                <a href="home.html" class="btn">Back to Home</a>
            </div>
        <?php elseif ($tracking_info): ?>
            
            <!-- Delivery Status Timeline -->
            <div class="tracking-status">
                <?php
                $statuses = ['assigned', 'picked_up', 'in_transit', 'delivered'];
                $current_status = $tracking_info['status'];
                
                foreach ($statuses as $index => $status) {
                    $class = '';
                    if ($status == $current_status) {
                        $class = 'active';
                    } elseif (array_search($status, $statuses) < array_search($current_status, $statuses)) {
                        $class = 'completed';
                    }
                    
                    $labels = [
                        'assigned' => 'Order Assigned',
                        'picked_up' => 'Picked Up',
                        'in_transit' => 'In Transit',
                        'delivered' => 'Delivered'
                    ];
                ?>
                    <div class="status-step <?php echo $class; ?>">
                        <div class="status-label"><?php echo $labels[$status]; ?></div>
                    </div>
                <?php } ?>
            </div>

            <!-- Current Status -->
            <div style="text-align: center; margin: 30px 0;">
                <h2>Current Status: <?php echo ucfirst(str_replace('_', ' ', $tracking_info['status'])); ?></h2>
                <?php if ($tracking_info['notes']): ?>
                    <p><em>"<?php echo htmlspecialchars($tracking_info['notes']); ?>"</em></p>
                <?php endif; ?>
            </div>

            <!-- Delivery Information -->
            <div class="delivery-info">
                <h3>📦 Order Details</h3>
                <p><strong>Order ID:</strong> #<?php echo $tracking_info['order_id']; ?></p>
                <p><strong>Customer:</strong> <?php echo htmlspecialchars($tracking_info['customer_name']); ?></p>
                <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($tracking_info['delivery_address']); ?></p>
                <p><strong>Order Total:</strong> ৳<?php echo number_format($tracking_info['total_price'], 2); ?></p>
                <p><strong>Assigned:</strong> <?php echo date('M d, Y H:i', strtotime($tracking_info['assigned_at'])); ?></p>
            </div>

            <!-- Driver Information -->
            <div class="driver-info">
                <h3>👨‍💼 Your Delivery Person</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($tracking_info['driver_name']); ?></p>
                <p><strong>Phone:</strong> <a href="tel:<?php echo $tracking_info['driver_phone']; ?>"><?php echo htmlspecialchars($tracking_info['driver_phone']); ?></a></p>
                <p><strong>Vehicle:</strong> <?php echo ucfirst($tracking_info['vehicle_type']); ?> (<?php echo htmlspecialchars($tracking_info['vehicle_number']); ?>)</p>
            </div>

            <!-- Estimated Delivery Time -->
            <?php if ($tracking_info['estimated_delivery_time']): ?>
                <div class="estimated-time">
                    <h3>⏰ Estimated Delivery Time</h3>
                    <p><strong><?php echo date('M d, Y H:i', strtotime($tracking_info['estimated_delivery_time'])); ?></strong></p>
                    <p>Your food will be delivered within this time frame.</p>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="home.html" class="btn">Back to Home</a>
                <a href="foods.php" class="btn">Order More Food</a>
            </div>

        <?php endif; ?>
    </div>

    <!-- Footer -->
    <section class="footer">
        <div class="container text-center">
            <p>All rights reserved. Designed By <a href="#">Ghoroa Khabar</a></p>
        </div>
    </section>

</body>
</html> 