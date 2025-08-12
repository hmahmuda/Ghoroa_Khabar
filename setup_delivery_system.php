<?php
include 'db.php';

echo "<h2>🚚 Setting up Delivery System</h2>";

try {
    // Create delivery system tables
    $tables = [
        "delivery_persons" => "CREATE TABLE delivery_persons (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            email VARCHAR(100),
            vehicle_number VARCHAR(20),
            vehicle_type ENUM('bike', 'car', 'cycle') DEFAULT 'bike',
            is_available BOOLEAN DEFAULT TRUE,
            current_location VARCHAR(255),
            rating DECIMAL(3,2) DEFAULT 5.00,
            total_deliveries INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_available (is_available),
            INDEX idx_rating (rating)
        )",
        
        "delivery_areas" => "CREATE TABLE delivery_areas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            area_name VARCHAR(100) NOT NULL,
            postcode VARCHAR(10),
            delivery_fee DECIMAL(10,2) DEFAULT 0.00,
            estimated_time INT DEFAULT 30,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_active (is_active),
            INDEX idx_postcode (postcode)
        )",
        
        "delivery_assignments" => "CREATE TABLE delivery_assignments (
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
        )",
        
        "delivery_tracking" => "CREATE TABLE delivery_tracking (
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
        )"
    ];
    
    foreach ($tables as $table_name => $sql) {
        // Check if table already exists
        $check_table = $conn->query("SHOW TABLES LIKE '$table_name'");
        if ($check_table->num_rows > 0) {
            echo "<p>⚠️ Table '$table_name' already exists - skipping creation</p>";
        } else {
            if ($conn->query($sql) === TRUE) {
                echo "<p>✅ Table '$table_name' created successfully</p>";
            } else {
                echo "<p>❌ Table '$table_name' creation: " . $conn->error . "</p>";
            }
        }
    }
    
    // Check if sample data already exists
    $check_persons = $conn->query("SELECT COUNT(*) as count FROM delivery_persons");
    $persons_count = $check_persons->fetch_assoc()['count'];
    
    $check_areas = $conn->query("SELECT COUNT(*) as count FROM delivery_areas");
    $areas_count = $check_areas->fetch_assoc()['count'];
    
    if ($persons_count == 0) {
        // Insert sample delivery persons with Sylhet names
        $insert_persons = "INSERT INTO delivery_persons (name, phone, email, vehicle_number, vehicle_type, current_location) VALUES
        ('Rahim Ali', '01712345678', 'rahim@ghoroa.com', 'SYL-1234', 'bike', 'Zindabazar, Sylhet'),
        ('Karim Uddin', '01787654321', 'karim@ghoroa.com', 'SYL-5678', 'bike', 'Bondor, Sylhet'),
        ('Fatima Begum', '01812345678', 'fatima@ghoroa.com', 'SYL-9012', 'car', 'Subhanighat, Sylhet'),
        ('Salam Mia', '01987654321', 'salam@ghoroa.com', 'SYL-3456', 'bike', 'Tilagor, Sylhet'),
        ('Abdul Kader', '01612345678', 'kader@ghoroa.com', 'SYL-7890', 'bike', 'Mirabazar, Sylhet'),
        ('Nusrat Jahan', '01587654321', 'nusrat@ghoroa.com', 'SYL-2345', 'bike', 'Kumarpara, Sylhet')";
        
        if ($conn->query($insert_persons) === TRUE) {
            echo "<p>✅ Sample delivery persons inserted successfully</p>";
        } else {
            echo "<p>⚠️ Sample delivery persons insertion: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>⚠️ Delivery persons data already exists ($persons_count records) - skipping insertion</p>";
    }
    
    if ($areas_count == 0) {
        // Insert sample delivery areas from Sylhet Sadar
        $insert_areas = "INSERT INTO delivery_areas (area_name, postcode, delivery_fee, estimated_time) VALUES
        ('Zindabazar', '3100', 40.00, 15),
        ('Bondor', '3100', 35.00, 12),
        ('Subhanighat', '3100', 45.00, 18),
        ('Tilagor', '3100', 30.00, 10),
        ('Mirabazar', '3100', 50.00, 20),
        ('Kumarpara', '3100', 35.00, 12),
        ('Shahjalal Upashahar', '3100', 55.00, 22),
        ('Airport Road', '3100', 60.00, 25),
        ('Shibganj', '3100', 40.00, 15),
        ('Chowhatta', '3100', 30.00, 10),
        ('Kajalshah', '3100', 35.00, 12),
        ('Taltala', '3100', 45.00, 18),
        ('Bagbari', '3100', 40.00, 15),
        ('Lamabazar', '3100', 50.00, 20),
        ('Uposhohor', '3100', 55.00, 22),
        ('Jalalabad', '3100', 65.00, 28),
        ('Khadimpara', '3100', 70.00, 30),
        ('Biswanath', '3100', 75.00, 35),
        ('Golapganj', '3100', 80.00, 40),
        ('Beanibazar', '3100', 85.00, 45)";
        
        if ($conn->query($insert_areas) === TRUE) {
            echo "<p>✅ Sample delivery areas inserted successfully</p>";
        } else {
            echo "<p>⚠️ Sample delivery areas insertion: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>⚠️ Delivery areas data already exists ($areas_count records) - skipping insertion</p>";
    }
    
    echo "<h3>🎉 Delivery system setup completed!</h3>";
    echo "<p><strong>Current Status:</strong></p>";
    
    // Show current table status
    $all_tables = ['delivery_persons', 'delivery_areas', 'delivery_assignments', 'delivery_tracking'];
    foreach ($all_tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            $count_result = $conn->query("SELECT COUNT(*) as count FROM $table");
            $count = $count_result->fetch_assoc()['count'];
            echo "<p>✅ Table '$table' exists with $count records</p>";
        } else {
            echo "<p>❌ Table '$table' is missing</p>";
        }
    }
    
    echo "<p><strong>Features Available:</strong></p>";
    echo "<ul>";
    echo "<li>✅ Delivery Person Management</li>";
    echo "<li>✅ Delivery Area Configuration</li>";
    echo "<li>✅ Delivery Assignment System</li>";
    echo "<li>✅ Delivery Tracking</li>";
    echo "<li>✅ Delivery Time Estimation</li>";
    echo "<li>✅ Delivery Area Restrictions</li>";
    echo "</ul>";
    
    echo "<p><strong>🌿 Sylhet Sadar Areas Added:</strong></p>";
    echo "<ul>";
    echo "<li>Zindabazar - ৳40 (15 min)</li>";
    echo "<li>Bondor - ৳35 (12 min)</li>";
    echo "<li>Subhanighat - ৳45 (18 min)</li>";
    echo "<li>Tilagor - ৳30 (10 min)</li>";
    echo "<li>Mirabazar - ৳50 (20 min)</li>";
    echo "<li>Kumarpara - ৳35 (12 min)</li>";
    echo "<li>Shahjalal Upashahar - ৳55 (22 min)</li>";
    echo "<li>Airport Road - ৳60 (25 min)</li>";
    echo "<li>And 12 more areas...</li>";
    echo "</ul>";
    
    echo "<p><a href='admin_delivery.php' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Go to Delivery Management</a></p>";
    echo "<p><a href='fix_delivery_tables.php' style='background: #ffc107; color: #212529; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Fix Tables (if needed)</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?> 