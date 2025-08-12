<?php
include 'db.php';

echo "<h2>🥬 Updating Bengali Side Dishes (Vortas)</h2>";

try {
    // First, let's clear existing side dishes
    $conn->query("DELETE FROM dishes WHERE category_id = 3");
    echo "<p>✅ Cleared existing side dishes</p>";
    
    // Insert new Bengali vorta and side dishes
    $side_dishes = [
        ['Alu Vorta', 80.00, 'alu-vorta.jpg'],
        ['Begun Vorta', 70.00, 'begun-vorta.jpg'],
        ['Dal Vorta', 60.00, 'dal-vorta.jpg'],
        ['Shutki Vorta', 120.00, 'shutki-vorta.jpg'],
        ['Muri Ghonto', 90.00, 'muri-ghonto.jpg'],
        ['Chingri Vorta', 100.00, 'chingri-vorta.jpg'],
        ['Macher Matha Vorta', 85.00, 'macher-matha.jpg'],
        ['Alu Begun Vorta', 75.00, 'alu-begun-vorta.jpg'],
        ['Piyaj Vorta', 65.00, 'piyaj-vorta.jpg'],
        ['Tomato Vorta', 70.00, 'tomato-vorta.jpg'],
        ['Brinjal Bharta', 80.00, 'brinjal-bharta.jpg'],
        ['Alu Posto', 85.00, 'alu-posto.jpg']
    ];
    
    $stmt = $conn->prepare("INSERT INTO dishes (name, price, category_id, image) VALUES (?, ?, 3, ?)");
    
    foreach ($side_dishes as $dish) {
        $stmt->bind_param("sds", $dish[0], $dish[1], $dish[2]);
        if ($stmt->execute()) {
            echo "<p>✅ Added: {$dish[0]} - ৳{$dish[1]}</p>";
        } else {
            echo "<p>❌ Failed to add: {$dish[0]} - " . $stmt->error . "</p>";
        }
    }
    
    $stmt->close();
    
    echo "<h3>🎉 Side Dishes updated successfully!</h3>";
    echo "<p><strong>New Bengali Side Dishes Added:</strong></p>";
    echo "<ul>";
    foreach ($side_dishes as $dish) {
        echo "<li><strong>{$dish[0]}</strong> - ৳{$dish[1]}</li>";
    }
    echo "</ul>";
    
    echo "<h4>🥬 Traditional Bengali Vortas:</h4>";
    echo "<ul>";
    echo "<li><strong>Alu Vorta</strong> - Mashed potato with mustard oil and spices</li>";
    echo "<li><strong>Begun Vorta</strong> - Mashed eggplant with onions and green chilies</li>";
    echo "<li><strong>Dal Vorta</strong> - Mashed lentils with mustard oil</li>";
    echo "<li><strong>Shutki Vorta</strong> - Dried fish mash with onions and chilies</li>";
    echo "<li><strong>Chingri Vorta</strong> - Mashed prawns with spices</li>";
    echo "<li><strong>Macher Matha Vorta</strong> - Fish head mash with mustard</li>";
    echo "<li><strong>Alu Begun Vorta</strong> - Mixed potato and eggplant mash</li>";
    echo "<li><strong>Piyaj Vorta</strong> - Onion mash with mustard oil</li>";
    echo "<li><strong>Tomato Vorta</strong> - Mashed tomatoes with spices</li>";
    echo "</ul>";
    
    echo "<h4>🍆 Other Side Dishes:</h4>";
    echo "<ul>";
    echo "<li><strong>Muri Ghonto</strong> - Fish head with rice and vegetables</li>";
    echo "<li><strong>Brinjal Bharta</strong> - Roasted eggplant preparation</li>";
    echo "<li><strong>Alu Posto</strong> - Potato with poppy seeds</li>";
    echo "</ul>";
    
    echo "<p><strong>Note:</strong> Vortas are traditional Bengali side dishes made by mashing ingredients with mustard oil, onions, and green chilies. They are typically served with rice.</p>";
    
    echo "<p><a href='category-foods.php?category_id=3' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>View Side Dishes</a></p>";
    echo "<p><a href='foods.php' style='background: #2f3542; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>View All Foods</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?> 