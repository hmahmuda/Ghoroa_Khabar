<?php
include 'db.php';
session_start();

echo "<h2>🔍 Admin Access Test</h2>";

// Check login status
if (is_logged_in()) {
    echo "<p>✅ <strong>Login Status:</strong> Logged in</p>";
    echo "<p>👤 <strong>User:</strong> " . htmlspecialchars($_SESSION['name']) . "</p>";
    echo "<p>🔑 <strong>Role:</strong> " . htmlspecialchars($_SESSION['role']) . "</p>";
    echo "<p>📧 <strong>Email:</strong> " . htmlspecialchars($_SESSION['email']) . "</p>";
    
    if ($_SESSION['role'] === 'admin') {
        echo "<h3>🎉 You are logged in as ADMIN!</h3>";
        echo "<p><strong>Available Admin Features:</strong></p>";
        echo "<ul>";
        echo "<li>✅ <a href='admin_dashboard.php'>Admin Dashboard</a></li>";
        echo "<li>✅ <a href='admin_delivery.php'>Delivery Management</a></li>";
        echo "<li>✅ <a href='setup_delivery_system.php'>Setup Delivery System</a></li>";
        echo "<li>✅ <a href='check_delivery_status.php'>Check Delivery Status</a></li>";
        echo "</ul>";
        
        echo "<h3>🚚 Quick Delivery Management:</h3>";
        echo "<p><a href='admin_delivery.php' style='background: #ff6b6b; color: white; padding: 15px 25px; text-decoration: none; border-radius: 5px; font-size: 16px;'>🚚 Go to Delivery Management</a></p>";
        
    } else {
        echo "<p>❌ <strong>Access Denied:</strong> You are not an admin user.</p>";
        echo "<p>Current role: " . htmlspecialchars($_SESSION['role']) . "</p>";
    }
} else {
    echo "<p>❌ <strong>Login Status:</strong> Not logged in</p>";
    echo "<p><a href='login.html'>Login here</a></p>";
}

echo "<hr>";
echo "<h3>🔧 Debug Information:</h3>";
echo "<p><strong>Session Data:</strong></p>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

echo "<p><strong>Available Files:</strong></p>";
$admin_files = [
    'admin_dashboard.php' => 'Admin Dashboard',
    'admin_delivery.php' => 'Delivery Management',
    'setup_delivery_system.php' => 'Setup Delivery System',
    'check_delivery_status.php' => 'Check Delivery Status'
];

foreach ($admin_files as $file => $description) {
    if (file_exists($file)) {
        echo "<p>✅ <a href='$file'>$file</a> - $description</p>";
    } else {
        echo "<p>❌ $file - $description (File not found)</p>";
    }
}
?> 