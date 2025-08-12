<?php
include 'db.php';
session_start();

echo "<h2>Database Debug Information</h2>";

// Check if user is logged in
if (is_logged_in()) {
    echo "<p>✅ User logged in: " . htmlspecialchars($_SESSION['name']) . " (ID: " . $_SESSION['user_id'] . ")</p>";
} else {
    echo "<p>❌ No user logged in</p>";
}

// Check dishes table
echo "<h3>Dishes in Database:</h3>";
$stmt = $conn->prepare("SELECT * FROM dishes");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $dishes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    if (!empty($dishes)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Price</th><th>Category ID</th><th>Available</th></tr>";
        foreach ($dishes as $dish) {
            echo "<tr>";
            echo "<td>" . $dish['id'] . "</td>";
            echo "<td>" . htmlspecialchars($dish['name']) . "</td>";
            echo "<td>৳" . $dish['price'] . "</td>";
            echo "<td>" . $dish['category_id'] . "</td>";
            echo "<td>" . ($dish['is_available'] ? 'Yes' : 'No') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ No dishes found in database</p>";
    }
} else {
    echo "<p>❌ Error preparing dishes query</p>";
}

// Check orders table
echo "<h3>Orders in Database:</h3>";
$stmt = $conn->prepare("SELECT o.*, u.name as user_name, d.name as dish_name 
                       FROM orders o 
                       LEFT JOIN users u ON o.user_id = u.id 
                       LEFT JOIN dishes d ON o.dish_id = d.id 
                       ORDER BY o.order_date DESC");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    if (!empty($orders)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Order ID</th><th>User</th><th>Dish</th><th>Quantity</th><th>Total Price</th><th>Status</th><th>Date</th></tr>";
        foreach ($orders as $order) {
            echo "<tr>";
            echo "<td>" . $order['id'] . "</td>";
            echo "<td>" . htmlspecialchars($order['user_name']) . "</td>";
            echo "<td>" . htmlspecialchars($order['dish_name']) . "</td>";
            echo "<td>" . $order['quantity'] . "</td>";
            echo "<td>৳" . $order['total_price'] . "</td>";
            echo "<td>" . $order['order_status'] . "</td>";
            echo "<td>" . $order['order_date'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ No orders found in database</p>";
    }
} else {
    echo "<p>❌ Error preparing orders query</p>";
}

// Check users table
echo "<h3>Users in Database:</h3>";
$stmt = $conn->prepare("SELECT id, name, email, role FROM users");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    if (!empty($users)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . htmlspecialchars($user['name']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . $user['role'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ No users found in database</p>";
    }
} else {
    echo "<p>❌ Error preparing users query</p>";
}

echo "<h3>Test Order Creation:</h3>";
if (is_logged_in()) {
    echo "<p><a href='order.php?dish_id=1' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Test Order (Dish ID 1)</a></p>";
    echo "<p><a href='order.php?dish_id=2' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Test Order (Dish ID 2)</a></p>";
} else {
    echo "<p><a href='login.php' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Login First</a></p>";
}

echo "<p><a href='home.html' style='background: #2f3542; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Back to Home</a></p>";
?> 