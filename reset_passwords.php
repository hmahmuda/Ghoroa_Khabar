<?php
include 'db.php';

echo "<h2>Password Reset Script</h2>";

try {
    // Update admin password
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = 'admin@ghoroa.com'");
    if ($stmt) {
        $stmt->bind_param("s", $admin_password);
        if ($stmt->execute()) {
            echo "<p>✅ Admin password updated successfully</p>";
        } else {
            echo "<p>❌ Failed to update admin password: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    
    // Update customer password
    $customer_password = password_hash('customer123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = 'john@example.com'");
    if ($stmt) {
        $stmt->bind_param("s", $customer_password);
        if ($stmt->execute()) {
            echo "<p>✅ Customer password updated successfully</p>";
        } else {
            echo "<p>❌ Failed to update customer password: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    
    echo "<h3>🎉 Password reset completed!</h3>";
    echo "<p><strong>Updated Login Credentials:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> admin@ghoroa.com / admin123</li>";
    echo "<li><strong>Customer:</strong> john@example.com / customer123</li>";
    echo "</ul>";
    echo "<p><a href='login.php' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Try Login Now</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?> 