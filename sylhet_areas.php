<?php
include 'db.php';

echo "<h2>🌿  Delivery Areas to Sylhet Sadar</h2>";

try {
    // Check if delivery_areas table exists
    $check_table = $conn->query("SHOW TABLES LIKE 'delivery_areas'");
    if ($check_table->num_rows == 0) {
        echo "<p>❌ Delivery areas table doesn't exist. Please run setup_delivery_system.php first.</p>";
        exit();
    }
    
    // Clear existing areas
    $conn->query("DELETE FROM delivery_areas");
    echo "<p>✅ Cleared existing delivery areas</p>";
    
    // Insert new Sylhet Sadar areas
    $sylhet_areas = [
        ['Zindabazar', '3100', 40.00, 15],
        ['Bondor', '3100', 35.00, 12],
        ['Subhanighat', '3100', 45.00, 18],
        ['Tilagor', '3100', 30.00, 10],
        ['Mirabazar', '3100', 50.00, 20],
        ['Kumarpara', '3100', 35.00, 12],
        ['Shahjalal Upashahar', '3100', 55.00, 22],
        ['Airport Road', '3100', 60.00, 25],
        ['Shibganj', '3100', 40.00, 15],
        ['Chowhatta', '3100', 30.00, 10],
        ['Kajalshah', '3100', 35.00, 12],
        ['Taltala', '3100', 45.00, 18],
        ['Bagbari', '3100', 40.00, 15],
        ['Lamabazar', '3100', 50.00, 20],
        ['Uposhohor', '3100', 55.00, 22],
        ['Jalalabad', '3100', 65.00, 28],
        ['Khadimpara', '3100', 70.00, 30],
        ['Biswanath', '3100', 75.00, 35],
        ['Golapganj', '3100', 80.00, 40],
        ['Beanibazar', '3100', 85.00, 45]
    ];
    
    $insert_stmt = $conn->prepare("INSERT INTO delivery_areas (area_name, postcode, delivery_fee, estimated_time) VALUES (?, ?, ?, ?)");
    
    foreach ($sylhet_areas as $area) {
        $insert_stmt->bind_param("ssdi", $area[0], $area[1], $area[2], $area[3]);
        if ($insert_stmt->execute()) {
            echo "<p>✅ Added: {$area[0]} - ৳{$area[2]} ({$area[3]} min)</p>";
        } else {
            echo "<p>❌ Failed to add: {$area[0]} - " . $conn->error . "</p>";
        }
    }
    
    $insert_stmt->close();
    
    // Also update delivery persons to Sylhet locations
    echo "<h3>👥 Updating Delivery Persons to Sylhet</h3>";
    
    $update_persons = [
        ['Rahim Ali', 'Zindabazar, Sylhet', 'SYL-1234'],
        ['Karim Uddin', 'Bondor, Sylhet', 'SYL-5678'],
        ['Fatima Begum', 'Subhanighat, Sylhet', 'SYL-9012'],
        ['Salam Mia', 'Tilagor, Sylhet', 'SYL-3456'],
        ['Abdul Kader', 'Mirabazar, Sylhet', 'SYL-7890'],
        ['Nusrat Jahan', 'Kumarpara, Sylhet', 'SYL-2345']
    ];
    
    // Clear existing delivery persons
    $conn->query("DELETE FROM delivery_persons");
    echo "<p>✅ Cleared existing delivery persons</p>";
    
    $person_stmt = $conn->prepare("INSERT INTO delivery_persons (name, phone, email, vehicle_number, vehicle_type, current_location) VALUES (?, ?, ?, ?, ?, ?)");
    
    $phones = ['01712345678', '01787654321', '01812345678', '01987654321', '01612345678', '01587654321'];
    $emails = ['rahim@ghoroa.com', 'karim@ghoroa.com', 'fatima@ghoroa.com', 'salam@ghoroa.com', 'kader@ghoroa.com', 'nusrat@ghoroa.com'];
    $vehicle_types = ['bike', 'bike', 'car', 'bike', 'bike', 'bike'];
    
    foreach ($update_persons as $index => $person) {
        $phone = $phones[$index];
        $email = $emails[$index];
        $vehicle_type = $vehicle_types[$index];
        
        $person_stmt->bind_param("ssssss", $person[0], $phone, $email, $person[2], $vehicle_type, $person[1]);
        if ($person_stmt->execute()) {
            echo "<p>✅ Added: {$person[0]} - {$person[1]}</p>";
        } else {
            echo "<p>❌ Failed to add: {$person[0]} - " . $conn->error . "</p>";
        }
    }
    
    $person_stmt->close();
    
    echo "<h3>🎉 Sylhet Sadar Delivery Areas Updated Successfully!</h3>";
    
    // Show summary
    $area_count = $conn->query("SELECT COUNT(*) as count FROM delivery_areas")->fetch_assoc()['count'];
    $person_count = $conn->query("SELECT COUNT(*) as count FROM delivery_persons")->fetch_assoc()['count'];
    
    echo "<p><strong>Summary:</strong></p>";
    echo "<ul>";
    echo "<li>✅ $area_count Sylhet Sadar areas configured</li>";
    echo "<li>✅ $person_count delivery persons updated</li>";
    echo "<li>✅ All areas use postcode 3100 (Sylhet Sadar)</li>";
    echo "<li>✅ Delivery fees range from ৳30 to ৳85</li>";
    echo "<li>✅ Delivery times range from 10 to 45 minutes</li>";
    echo "</ul>";
    
    echo "<p><strong>🌿 Popular Areas:</strong></p>";
    echo "<ul>";
    echo "<li>Zindabazar - ৳40 (15 min) - City center</li>";
    echo "<li>Bondor - ৳35 (12 min) - Near railway station</li>";
    echo "<li>Subhanighat - ৳45 (18 min) - Riverside area</li>";
    echo "<li>Tilagor - ৳30 (10 min) - University area</li>";
    echo "<li>Shahjalal Upashahar - ৳55 (22 min) - Residential area</li>";
    echo "</ul>";
    
    echo "<p><a href='admin_delivery.php' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Go to Delivery Management</a></p>";
    echo "<p><a href='check_delivery_status.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Check Delivery Status</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?> 