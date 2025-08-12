<?php
include 'db.php';

echo "<h2>Login Test Script</h2>";

// Test password hashing
$test_password = 'admin123';
$hashed_password = password_hash($test_password, PASSWORD_DEFAULT);

echo "<h3>Password Hashing Test:</h3>";
echo "<p>Original password: $test_password</p>";
echo "<p>Hashed password: $hashed_password</p>";

// Test password verification
if (password_verify($test_password, $hashed_password)) {
    echo "<p>✅ Password verification works correctly</p>";
} else {
    echo "<p>❌ Password verification failed</p>";
}

// Check current passwords in database
echo "<h3>Current Database Passwords:</h3>";
$stmt = $conn->prepare("SELECT email, password FROM users");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    foreach ($users as $user) {
        echo "<p><strong>" . $user['email'] . ":</strong> " . substr($user['password'], 0, 20) . "...</p>";
        
        // Test if current password works
        if (password_verify('admin123', $user['password']) && $user['email'] == 'admin@ghoroa.com') {
            echo "<p>✅ Admin password works</p>";
        } elseif (password_verify('customer123', $user['password']) && $user['email'] == 'john@example.com') {
            echo "<p>✅ Customer password works</p>";
        } else {
            echo "<p>❌ Password doesn't match expected</p>";
        }
    }
} else {
    echo "<p>❌ Error querying users</p>";
}

echo "<h3>Actions:</h3>";
echo "<p><a href='reset_passwords.php' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Reset Passwords</a></p>";
echo "<p><a href='login.php' style='background: #2f3542; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Go to Login</a></p>";

$conn->close();
?> 