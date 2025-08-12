<?php
include 'db.php';

echo "<h2>🔧 Fixing Category Names</h2>";

try {
    // First, let's see what categories currently exist
    echo "<h3>Current Categories:</h3>";
    $result = $conn->query("SELECT id, name FROM categories ORDER BY id");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>ID {$row['id']}: {$row['name']}</p>";
        }
    }
    
    // Update category names to match what we want
    $category_updates = [
        [1, 'Fish Dishes', 'Fresh Bengali fish preparations'],
        [2, 'Rice & Breads', 'Rice dishes and breads'],
        [3, 'Side Dishes', 'Vegetables and accompaniments'],
        [4, 'Meat Dishes', 'Fresh meat preparations'],
        [5, 'Desserts', 'Sweet Bengali treats']
    ];
    
    $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
    
    foreach ($category_updates as $category) {
        $stmt->bind_param("ssi", $category[1], $category[2], $category[0]);
        if ($stmt->execute()) {
            echo "<p>✅ Updated Category {$category[0]}: {$category[1]}</p>";
        } else {
            echo "<p>❌ Failed to update Category {$category[0]}: " . $stmt->error . "</p>";
        }
    }
    
    $stmt->close();
    
    echo "<h3>Updated Categories:</h3>";
    $result = $conn->query("SELECT id, name FROM categories ORDER BY id");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>ID {$row['id']}: {$row['name']}</p>";
        }
    }
    
    echo "<h3>🎉 Category names fixed successfully!</h3>";
    echo "<p><a href='category-foods.php?category_id=1' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Test Fish Dishes</a></p>";
    echo "<p><a href='categories.html' style='background: #2f3542; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Back to Categories</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?> 