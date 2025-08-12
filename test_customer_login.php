<?php
include 'db.php';

echo "<h2>🧪 Testing Customer Login</h2>";

try {
    // Test customer login credentials
    $email = 'customer@ghoroa.com';
    $password = 'customer123';

    echo "<p><strong>Testing Login:</strong></p>";
    echo "<p>Email: $email</p>";
    echo "<p>Password: $password</p>";

    // Check if user exists
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "<p>✅ User found in database</p>";
        echo "<p>User ID: " . $user['id'] . "</p>";
        echo "<p>Name: " . htmlspecialchars($user['name']) . "</p>";
        echo "<p>Role: " . htmlspecialchars($user['role']) . "</p>";

        // Test password verification
        if (password_verify($password, $user['password'])) {
            echo "<p style='color: green;'>✅ Password verification successful!</p>";
            echo "<p>Customer login should work correctly.</p>";
        } else {
            echo "<p style='color: red;'>❌ Password verification failed!</p>";
            echo "<p>This means the password hash is incorrect.</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ User not found in database!</p>";
        echo "<p>Please run fix_customer_login.php to create the customer user.</p>";
    }

    $stmt->close();

    // Show all users
    echo "<h3>📋 All Users in Database:</h3>";
    $users_result = $conn->query("SELECT id, name, email, role FROM users ORDER BY id");

    if ($users_result->num_rows > 0) {
        echo "<table style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr style='background: #f8f9fa;'><th style='border: 1px solid #ddd; padding: 8px;'>ID</th><th style='border: 1px solid #ddd; padding: 8px;'>Name</th><th style='border: 1px solid #ddd; padding: 8px;'>Email</th><th style='border: 1px solid #ddd; padding: 8px;'>Role</th></tr>";

        while ($user = $users_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $user['id'] . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($user['name']) . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($user['role']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    echo "<h3>🔧 Quick Fix Options:</h3>";
    echo "<p><a href='fix_customer_login.php' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Fix Customer Login</a></p>";
    echo "<p><a href='login.html' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Test Login Page</a></p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
