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
$dish = null;

// Handle POST request (form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dish_id = (int)$_POST['dish_id'];
    $quantity = (int)$_POST['quantity'];
    $user_id = (int)$_SESSION['user_id'];
    
    // Validate quantity
    if ($quantity < 1 || $quantity > 50) {
        $error_message = "Invalid quantity. Please select between 1 and 50.";
    } else {
        // Get dish details
        $stmt = $conn->prepare("SELECT id, name, price FROM dishes WHERE id = ? AND is_available = 1");
        if ($stmt) {
            $stmt->bind_param("i", $dish_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
    $dish = $result->fetch_assoc();
    $total_price = $dish['price'] * $quantity;

                // Insert the order
                $order_stmt = $conn->prepare("INSERT INTO orders (user_id, dish_id, quantity, total_price, order_status, payment_status, order_date) 
                                            VALUES (?, ?, ?, ?, 'pending', 'pending', NOW())");
                if ($order_stmt) {
                    $order_stmt->bind_param("iiid", $user_id, $dish_id, $quantity, $total_price);
                    
                    if ($order_stmt->execute()) {
                        $order_id = $conn->insert_id;
                        $success_message = "Order placed successfully! Order ID: #" . $order_id;
                    } else {
                        $error_message = "Failed to place order. Please try again.";
                    }
                    $order_stmt->close();
                } else {
                    $error_message = "Database error occurred. Please try again.";
                }
            } else {
                $error_message = "Dish not found or not available!";
            }
            $stmt->close();
        } else {
            $error_message = "Database error occurred. Please try again.";
        }
    }
}

// Handle GET request (direct dish link)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['dish_id'])) {
    $dish_id = (int)$_GET['dish_id'];
    
    // Get dish details
    $stmt = $conn->prepare("SELECT id, name, price FROM dishes WHERE id = ? AND is_available = 1");
    if ($stmt) {
        $stmt->bind_param("i", $dish_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $dish = $result->fetch_assoc();
        } else {
            $error_message = "Dish not found or not available!";
        }
        $stmt->close();
    } else {
        $error_message = "Database error occurred. Please try again.";
    }
}

// If no dish_id provided, show error
if (!$dish && !$error_message && !$success_message) {
    $error_message = "No dish selected for ordering.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order | Ghoroa Khabar</title>
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

<!-- Order Status or Form -->
<section class="food-search text-center">
    <div class="container">
        <?php if ($error_message): ?>
            <div class="error-message" style="color: red; margin: 10px 0; background: #ffe6e6; padding: 15px; border-radius: 4px;">
                <?php echo $error_message; ?>
            </div>
            <div style="margin-top: 20px;">
                <a href="foods.php" style="background-color: #ff6b6b; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px;">Back to Foods</a>
            </div>
        <?php elseif ($success_message): ?>
            <div class="success-message" style="color: green; margin: 10px 0; background: #e6ffe6; padding: 15px; border-radius: 4px;">
                <?php echo $success_message; ?>
                <br><br>
                <a href="foods.php" style="color: #ff6b6b; margin-right: 20px;">Continue Shopping</a>
                <a href="payment.php" style="color: #ff6b6b;">Make Payment</a>
            </div>
            <div style="margin-top: 20px;">
                <a href="foods.php" style="background-color: #ff6b6b; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px;">Back to Foods</a>
            </div>
        <?php elseif ($dish): ?>
            <h2>Order: <?php echo htmlspecialchars($dish['name']); ?></h2>
            
            <form method="POST" action="order.php" style="max-width: 500px; margin: 0 auto; padding: 20px;">
                <input type="hidden" name="dish_id" value="<?php echo $dish['id']; ?>">
                
                <div style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
                    <h3><?php echo htmlspecialchars($dish['name']); ?></h3>
                    <p><strong>Price:</strong> ৳<?php echo number_format($dish['price'], 2); ?></p>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label for="quantity" style="display: block; margin-bottom: 5px;">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="50" required 
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <button type="submit" style="background-color: #ff6b6b; color: white; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer; width: 100%;">
                    Place Order
                </button>
            </form>
            
            <div style="margin-top: 20px;">
                <a href="foods.php" style="background-color: #2f3542; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px;">Back to Foods</a>
            </div>
        <?php endif; ?>
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
