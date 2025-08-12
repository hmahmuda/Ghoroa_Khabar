<?php
include 'db.php';

echo "<h2>🔍 Delivery System Status Check</h2>";

try {
    $delivery_tables = ['delivery_persons', 'delivery_areas', 'delivery_assignments', 'delivery_tracking'];
    
    echo "<h3>📊 Table Status:</h3>";
    foreach ($delivery_tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            $count_result = $conn->query("SELECT COUNT(*) as count FROM $table");
            $count = $count_result->fetch_assoc()['count'];
            echo "<p>✅ <strong>$table</strong> - Exists with $count records</p>";
        } else {
            echo "<p>❌ <strong>$table</strong> - Missing</p>";
        }
    }
    
    echo "<h3>👥 Delivery Persons:</h3>";
    $persons = $conn->query("SELECT name, phone, vehicle_type, is_available FROM delivery_persons");
    if ($persons->num_rows > 0) {
        echo "<table style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr style='background: #f8f9fa;'><th style='border: 1px solid #ddd; padding: 8px;'>Name</th><th style='border: 1px solid #ddd; padding: 8px;'>Phone</th><th style='border: 1px solid #ddd; padding: 8px;'>Vehicle</th><th style='border: 1px solid #ddd; padding: 8px;'>Available</th></tr>";
        while ($person = $persons->fetch_assoc()) {
            $available = $person['is_available'] ? 'Yes' : 'No';
            echo "<tr><td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($person['name']) . "</td><td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($person['phone']) . "</td><td style='border: 1px solid #ddd; padding: 8px;'>" . ucfirst($person['vehicle_type']) . "</td><td style='border: 1px solid #ddd; padding: 8px;'>$available</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No delivery persons found.</p>";
    }
    
    echo "<h3>🗺️ Delivery Areas:</h3>";
    $areas = $conn->query("SELECT area_name, postcode, delivery_fee, estimated_time, is_active FROM delivery_areas");
    if ($areas->num_rows > 0) {
        echo "<table style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr style='background: #f8f9fa;'><th style='border: 1px solid #ddd; padding: 8px;'>Area</th><th style='border: 1px solid #ddd; padding: 8px;'>Postcode</th><th style='border: 1px solid #ddd; padding: 8px;'>Fee</th><th style='border: 1px solid #ddd; padding: 8px;'>Time (min)</th><th style='border: 1px solid #ddd; padding: 8px;'>Active</th></tr>";
        while ($area = $areas->fetch_assoc()) {
            $active = $area['is_active'] ? 'Yes' : 'No';
            echo "<tr><td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($area['area_name']) . "</td><td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($area['postcode']) . "</td><td style='border: 1px solid #ddd; padding: 8px;'>৳" . number_format($area['delivery_fee'], 2) . "</td><td style='border: 1px solid #ddd; padding: 8px;'>" . $area['estimated_time'] . "</td><td style='border: 1px solid #ddd; padding: 8px;'>$active</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No delivery areas found.</p>";
    }
    
    echo "<h3>📦 Delivery Assignments:</h3>";
    $assignments = $conn->query("SELECT COUNT(*) as count FROM delivery_assignments");
    $assignment_count = $assignments->fetch_assoc()['count'];
    echo "<p>Total assignments: $assignment_count</p>";
    
    if ($assignment_count > 0) {
        $recent_assignments = $conn->query("SELECT da.id, da.order_id, da.status, da.assigned_at, dp.name as driver_name FROM delivery_assignments da JOIN delivery_persons dp ON da.delivery_person_id = dp.id ORDER BY da.assigned_at DESC LIMIT 5");
        echo "<p><strong>Recent assignments:</strong></p>";
        echo "<table style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr style='background: #f8f9fa;'><th style='border: 1px solid #ddd; padding: 8px;'>ID</th><th style='border: 1px solid #ddd; padding: 8px;'>Order</th><th style='border: 1px solid #ddd; padding: 8px;'>Driver</th><th style='border: 1px solid #ddd; padding: 8px;'>Status</th><th style='border: 1px solid #ddd; padding: 8px;'>Assigned</th></tr>";
        while ($assignment = $recent_assignments->fetch_assoc()) {
            echo "<tr><td style='border: 1px solid #ddd; padding: 8px;'>" . $assignment['id'] . "</td><td style='border: 1px solid #ddd; padding: 8px;'>#" . $assignment['order_id'] . "</td><td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($assignment['driver_name']) . "</td><td style='border: 1px solid #ddd; padding: 8px;'>" . ucfirst($assignment['status']) . "</td><td style='border: 1px solid #ddd; padding: 8px;'>" . date('M d, Y H:i', strtotime($assignment['assigned_at'])) . "</td></tr>";
        }
        echo "</table>";
    }
    
    echo "<h3>🎯 System Readiness:</h3>";
    $all_tables_exist = true;
    foreach ($delivery_tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows == 0) {
            $all_tables_exist = false;
            break;
        }
    }
    
    if ($all_tables_exist) {
        echo "<p style='color: green; font-weight: bold;'>✅ Delivery system is ready to use!</p>";
        echo "<p><a href='admin_delivery.php' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Go to Delivery Management</a></p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ Delivery system needs setup!</p>";
        echo "<p><a href='setup_delivery_system.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Run Setup</a></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?> 