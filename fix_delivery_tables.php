<?php
include 'db.php';

echo "<h2>🔧 Fixing Delivery Tables</h2>";

try {
    // Drop the problematic delivery_assignments table
    $conn->query("DROP TABLE IF EXISTS delivery_assignments");
    echo "<p>✅ Dropped existing delivery_assignments table</p>";
    
    // Recreate the delivery_assignments table with correct TIMESTAMP NULL values
    $create_assignments = "CREATE TABLE delivery_assignments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        delivery_person_id INT NOT NULL,
        assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        estimated_pickup_time TIMESTAMP NULL,
        estimated_delivery_time TIMESTAMP NULL,
        actual_pickup_time TIMESTAMP NULL,
        actual_delivery_time TIMESTAMP NULL,
        status ENUM('assigned', 'picked_up', 'in_transit', 'delivered', 'cancelled') DEFAULT 'assigned',
        delivery_fee DECIMAL(10,2) DEFAULT 0.00,
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_order (order_id),
        INDEX idx_delivery_person (delivery_person_id),
        INDEX idx_status (status)
    )";
    
    if ($conn->query($create_assignments) === TRUE) {
        echo "<p>✅ Recreated delivery_assignments table successfully</p>";
    } else {
        echo "<p>❌ Error recreating delivery_assignments table: " . $conn->error . "</p>";
    }
    
    // Check if delivery_tracking table exists, if not create it
    $check_tracking = $conn->query("SHOW TABLES LIKE 'delivery_tracking'");
    if ($check_tracking->num_rows == 0) {
        $create_tracking = "CREATE TABLE delivery_tracking (
            id INT AUTO_INCREMENT PRIMARY KEY,
            assignment_id INT NOT NULL,
            status ENUM('assigned', 'picked_up', 'in_transit', 'delivered', 'cancelled') NOT NULL,
            location VARCHAR(255),
            latitude DECIMAL(10,8),
            longitude DECIMAL(11,8),
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_assignment (assignment_id),
            INDEX idx_status (status)
        )";
        
        if ($conn->query($create_tracking) === TRUE) {
            echo "<p>✅ Created delivery_tracking table successfully</p>";
        } else {
            echo "<p>❌ Error creating delivery_tracking table: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>✅ delivery_tracking table already exists</p>";
    }
    
    echo "<h3>🎉 Delivery tables fixed successfully!</h3>";
    echo "<p><strong>Tables Status:</strong></p>";
    
    // Check all delivery tables
    $tables = ['delivery_persons', 'delivery_areas', 'delivery_assignments', 'delivery_tracking'];
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "<p>✅ Table '$table' exists and is ready</p>";
        } else {
            echo "<p>❌ Table '$table' is missing</p>";
        }
    }
    
    echo "<p><a href='admin_delivery.php' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Go to Delivery Management</a></p>";
    echo "<p><a href='setup_delivery_system.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Run Full Setup Again</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?> 