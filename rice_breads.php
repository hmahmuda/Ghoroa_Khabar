<?php
include 'db.php';

echo "<h2>🍚  Bengali Rice & Breads</h2>";

try {
    // First, let's clear existing rice & breads dishes
    $conn->query("DELETE FROM dishes WHERE category_id = 2");
    echo "<p>✅ Cleared existing rice & breads dishes</p>";
    
    // Insert new Bengali rice and bread dishes
    $rice_breads = [
        ['Bhuna Khichuri', 180.00, 'khichuri.webp'],
        ['Plain Steamed Rice', 40.00, 'rice.jpg'],
        ['Ghee Bhaat', 60.00, 'ghee-bhaat.jpg'],
        ['Jeera Rice', 50.00, 'jeera-rice.jpg'],
        ['Lemon Rice', 55.00, 'lemon-rice.jpg'],
        ['Pulao', 120.00, 'pulao.jpg'],
        ['Luchi', 15.00, 'luchi.jpg'],
        ['Paratha', 20.00, 'paratha.jpg'],
        ['Roti', 12.00, 'roti.jpg'],
        ['Naan', 25.00, 'naan.jpg']
    ];
    
    $stmt = $conn->prepare("INSERT INTO dishes (name, price, category_id, image) VALUES (?, ?, 2, ?)");
    
    foreach ($rice_breads as $dish) {
        $stmt->bind_param("sds", $dish[0], $dish[1], $dish[2]);
        if ($stmt->execute()) {
            echo "<p>✅ Added: {$dish[0]} - ৳{$dish[1]}</p>";
        } else {
            echo "<p>❌ Failed to add: {$dish[0]} - " . $stmt->error . "</p>";
        }
    }
    
    $stmt->close();
    
    echo "<h3>🎉 Rice & Breads updated successfully!</h3>";
    echo "<p><strong>New Rice & Bread Items Added:</strong></p>";
    echo "<ul>";
    foreach ($rice_breads as $dish) {
        echo "<li><strong>{$dish[0]}</strong> - ৳{$dish[1]}</li>";
    }
    echo "</ul>";
    
    echo "<h4>🍚 Rice Dishes:</h4>";
    echo "<ul>";
    echo "<li><strong>Bhuna Khichuri</strong> - Traditional Bengali khichuri with spices</li>";
    echo "<li><strong>Plain Steamed Rice</strong> - Soft, fluffy rice</li>";
    echo "<li><strong>Ghee Bhaat</strong> - Rice cooked with ghee and spices</li>";
    echo "<li><strong>Jeera Rice</strong> - Rice with cumin seeds</li>";
    echo "<li><strong>Lemon Rice</strong> - Tangy rice with lemon</li>";
    echo "<li><strong>Pulao</strong> - Fragrant rice with vegetables</li>";
    echo "</ul>";
    
    echo "<h4>🥖 Bread Items:</h4>";
    echo "<ul>";
    echo "<li><strong>Luchi</strong> - Deep-fried puffed bread</li>";
    echo "<li><strong>Paratha</strong> - Layered flatbread</li>";
    echo "<li><strong>Roti</strong> - Whole wheat flatbread</li>";
    echo "<li><strong>Naan</strong> - Leavened flatbread</li>";
    echo "</ul>";
    
    echo "<p><a href='category-foods.php?category_id=2' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>View Rice & Breads</a></p>";
    echo "<p><a href='foods.php' style='background: #2f3542; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>View All Foods</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?> 