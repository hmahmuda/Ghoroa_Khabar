<?php
include 'db.php';

echo "<h2>🔧 Fixing Customer Login</h2>";

try {
    // Check if customer@ghoroa.com already exists
    $check_stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
    $customer_email = 'customer@ghoroa.com';
    $check_stmt->bind_param("s", $customer_email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p>✅ Customer user 'customer@ghoroa.com' already exists</p>";

        // Update the password to ensure it's correct
        $hashed_password = password_hash('customer123', PASSWORD_DEFAULT);
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update_stmt->bind_param("ss", $hashed_password, $customer_email);

        if ($update_stmt->execute()) {
            echo "<p>✅ Customer password updated successfully</p>";
        } else {
            echo "<p>❌ Error updating customer password: " . $conn->error . "</p>";
        }
        $update_stmt->close();
    } else {
        echo "<p>⚠️ Customer user 'customer@ghoroa.com' not found, creating new user...</p>";

        // Create new customer user
        $hashed_password = password_hash('customer123', PASSWORD_DEFAULT);
        $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, role, contact, address) VALUES (?, ?, ?, ?, ?, ?)");
        $name = 'Customer User';
        $role = 'customer';
        $contact = '01787654321';
        $address = 'Sylhet, Bangladesh';

        $insert_stmt->bind_param("ssssss", $name, $customer_email, $hashed_password, $role, $contact, $address);

        if ($insert_stmt->execute()) {
            echo "<p>✅ Customer user created successfully</p>";
        } else {
            echo "<p>❌ Error creating customer user: " . $conn->error . "</p>";
        }
        $insert_stmt->close();
    }

    $check_stmt->close();

    // Show all users in the database
    echo "<h3>📋 Current Users in Database:</h3>";
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
    } else {
        echo "<p>No users found in database.</p>";
    }

    echo "<h3>🎉 Customer Login Fixed!</h3>";
    echo "<p><strong>Login Credentials:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> admin@ghoroa.com / admin123</li>";
    echo "<li><strong>Customer:</strong> customer@ghoroa.com / customer123</li>";
    echo "</ul>";

    echo "<p><a href='login.html' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Test Login</a></p>";
    echo "<p><a href='home.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Go to Homepage</a></p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
