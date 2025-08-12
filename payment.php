<?php
include 'db.php';
session_start();

// Check if user is logged in
if (!is_logged_in()) {
    echo "<div style='text-align: center; padding: 50px;'>";
    echo "<h2>Please log in first!</h2>";
    echo "<a href='login.php' style='color: #ff6b6b;'>Login here</a>";
    echo "</div>";
    exit();
}

$error_message = '';
$success_message = '';
$user_orders = [];

// Fetch user's pending orders
$stmt = $conn->prepare("SELECT o.id, o.total_price, o.order_date, d.name as dish_name 
                       FROM orders o 
                       JOIN dishes d ON o.dish_id = d.id 
                       WHERE o.user_id = ? AND o.payment_status = 'pending' 
                       ORDER BY o.order_date DESC");
if ($stmt) {
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_orders = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];
    $payment_method = sanitize_input($_POST['payment_method']);
    $amount = (float)$_POST['amount'];
    
    // Validate payment method
    $valid_methods = ['cash', 'bkash', 'online'];
    if (!in_array($payment_method, $valid_methods)) {
        $error_message = "Invalid payment method selected.";
    } elseif ($amount <= 0) {
        $error_message = "Invalid amount.";
    } else {
        // Verify the order belongs to the current user and is pending
        $verify_stmt = $conn->prepare("SELECT id, total_price FROM orders WHERE id = ? AND user_id = ? AND payment_status = 'pending'");
        if ($verify_stmt) {
            $verify_stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
            $verify_stmt->execute();
            $order_result = $verify_stmt->get_result();
            
            if ($order_result->num_rows > 0) {
                $order = $order_result->fetch_assoc();
                
                // Check if amount matches order total
                if (abs($amount - $order['total_price']) < 0.01) { // Allow small floating point differences
                    // Insert payment using prepared statement
                    $payment_stmt = $conn->prepare("INSERT INTO payments (order_id, payment_method, amount, payment_date) VALUES (?, ?, ?, NOW())");
                    if ($payment_stmt) {
                        $payment_stmt->bind_param("isd", $order_id, $payment_method, $amount);
                        
                        if ($payment_stmt->execute()) {
                            // Update the order's payment status
                            $update_stmt = $conn->prepare("UPDATE orders SET payment_status = 'completed' WHERE id = ?");
                            if ($update_stmt) {
                                $update_stmt->bind_param("i", $order_id);
                                $update_stmt->execute();
                                $update_stmt->close();
                            }
                            
                            $success_message = "Payment successful! Your order has been confirmed.";
                            
                            // Refresh the orders list
                            $stmt = $conn->prepare("SELECT o.id, o.total_price, o.order_date, d.name as dish_name 
                                                   FROM orders o 
                                                   JOIN dishes d ON o.dish_id = d.id 
                                                   WHERE o.user_id = ? AND o.payment_status = 'pending' 
                                                   ORDER BY o.order_date DESC");
                            if ($stmt) {
                                $stmt->bind_param("i", $_SESSION['user_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $user_orders = $result->fetch_all(MYSQLI_ASSOC);
                                $stmt->close();
                            }
                        } else {
                            $error_message = "Payment failed. Please try again.";
                        }
                        $payment_stmt->close();
                    } else {
                        $error_message = "Database error occurred. Please try again.";
                    }
                } else {
                    $error_message = "Payment amount does not match order total.";
                }
            } else {
                $error_message = "Order not found or already paid.";
            }
            $verify_stmt->close();
        } else {
            $error_message = "Database error occurred. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | Ghoroa Khabar</title>
    <link rel="stylesheet" href="css/style.css">
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
                <li><a href="home.html">Home</a></li>
                <li><a href="categories.html">Categories</a></li>
                <li><a href="foods.php">Foods</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</section>

<!-- Payment Section -->
<section class="food-search text-center">
    <div class="container">
        <h2>Payment</h2>
        
        <?php if ($error_message): ?>
            <div class="error-message" style="color: red; margin: 10px 0; background: #ffe6e6; padding: 15px; border-radius: 4px;">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="success-message" style="color: green; margin: 10px 0; background: #e6ffe6; padding: 15px; border-radius: 4px;">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($user_orders)): ?>
            <div style="margin: 30px 0;">
                <h3>No pending payments</h3>
                <p>You don't have any orders waiting for payment.</p>
                <a href="foods.php" style="background-color: #ff6b6b; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px;">Order Food</a>
            </div>
        <?php else: ?>
            <div style="max-width: 800px; margin: 0 auto;">
                <h3>Your Pending Orders</h3>
                
                <?php foreach ($user_orders as $order): ?>
                    <div style="border: 1px solid #ddd; margin: 15px 0; padding: 20px; border-radius: 8px; background: #f9f9f9;">
                        <h4>Order #<?php echo $order['id']; ?></h4>
                        <p><strong>Dish:</strong> <?php echo htmlspecialchars($order['dish_name']); ?></p>
                        <p><strong>Total:</strong> ৳<?php echo number_format($order['total_price'], 2); ?></p>
                        <p><strong>Date:</strong> <?php echo date('M d, Y H:i', strtotime($order['order_date'])); ?></p>
                        
                        <form method="POST" action="payment.php" style="margin-top: 15px;">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <input type="hidden" name="amount" value="<?php echo $order['total_price']; ?>">
                            
                            <div style="margin-bottom: 15px;">
                                <label for="payment_method_<?php echo $order['id']; ?>" style="display: block; margin-bottom: 5px;">Payment Method:</label>
                                <select name="payment_method" id="payment_method_<?php echo $order['id']; ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                                    <option value="">Select Payment Method</option>
                                    <option value="cash">Cash on Delivery</option>
        <option value="bkash">bKash</option>
                                    <option value="online">Online Banking</option>
                                </select>
                            </div>
                            
                            <button type="submit" style="background-color: #ff6b6b; color: white; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer;">Pay ৳<?php echo number_format($order['total_price'], 2); ?></button>
</form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px;">
            <a href="foods.php" style="background-color: #ff6b6b; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px; margin-right: 10px;">Back to Foods</a>
            <a href="home.html" style="background-color: #2f3542; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px;">Home</a>
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
