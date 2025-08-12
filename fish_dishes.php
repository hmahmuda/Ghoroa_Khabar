<?php
include 'db.php';

echo "<h2>🐟 Updating Bengali Fish Dishes</h2>";

try {
    // First, let's clear existing dishes to avoid duplicates
    $conn->query("DELETE FROM dishes WHERE category_id = 1");
    echo "<p>✅ Cleared existing fish dishes</p>";
    
    // Insert new fish dishes
    $fish_dishes = [
        ['Ilish Fry', 450.00, 'ilish-mach.jpg'],
        ['Rui Macher Jhol', 220.00, 'rui-macher-jhol.webp'],
        ['Katla Macher Jhol', 200.00, 'katla-mach.jpg'],
        ['Pabda Macher Jhol', 180.00, 'pabda-mach.jpg'],
        ['Tengra Macher Jhol', 160.00, 'tengra-mach.jpg'],
        ['Chingri Macher Malai Curry', 350.00, 'chingri-malai.jpg'],
        ['Koi Macher Jhol', 190.00, 'koi-mach.jpg'],
        ['Bhetki Macher Paturi', 280.00, 'bhetki-paturi.jpg']
    ];
    
    $stmt = $conn->prepare("INSERT INTO dishes (name, price, category_id, image) VALUES (?, ?, 1, ?)");
    
    foreach ($fish_dishes as $dish) {
        $stmt->bind_param("sds", $dish[0], $dish[1], $dish[2]);
        if ($stmt->execute()) {
            echo "<p>✅ Added: {$dish[0]} - ৳{$dish[1]}</p>";
        } else {
            echo "<p>❌ Failed to add: {$dish[0]} - " . $stmt->error . "</p>";
        }
    }
    
    $stmt->close();
    
    echo "<h3>🎉 Fish dishes updated successfully!</h3>";
    echo "<p><strong>New Fish Dishes Added:</strong></p>";
    echo "<ul>";
    foreach ($fish_dishes as $dish) {
        echo "<li><strong>{$dish[0]}</strong> - ৳{$dish[1]}</li>";
    }
    echo "</ul>";
    
    echo "<p><a href='category-foods.php?category_id=1' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>View Fish Dishes</a></p>";
    echo "<p><a href='foods.php' style='background: #2f3542; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>View All Foods</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?>
