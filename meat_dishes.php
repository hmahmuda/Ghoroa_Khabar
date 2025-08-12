<?php
include 'db.php';

echo "<h2>🍖 Adding Bengali Meat Category</h2>";

try {
    // First, let's add the new Meat category
    $conn->query("INSERT INTO categories (id, name, description) VALUES (6, 'Meat Dishes', 'Traditional Bengali meat preparations')");
    echo "<p>✅ Added new 'Meat Dishes' category</p>";
    
    // Insert Bengali meat dishes
    $meat_dishes = [
        ['Gorur Mangsho', 350.00, 'gorur-mangsho.jpg'],
        ['Murgir Mangsho', 250.00, 'murgir-mangsho.jpg'],
        ['Khashir Mangsho', 400.00, 'khashir-mangsho.jpg'],
        ['Murgir Jhol', 200.00, 'murgir-jhol.jpg'],
        ['Gorur Bhuna', 380.00, 'gorur-bhuna.jpg'],
        ['Murgir Bhuna', 280.00, 'murgir-bhuna.jpg'],
        ['Khashir Bhuna', 420.00, 'khashir-bhuna.jpg'],
        ['Murgir Rezala', 300.00, 'murgir-rezala.jpg'],
        ['Gorur Rezala', 400.00, 'gorur-rezala.jpg'],
        ['Murgir Korma', 320.00, 'murgir-korma.jpg'],
        ['Gorur Korma', 420.00, 'gorur-korma.jpg'],
        ['Murgir Biryani', 280.00, 'murgir-biryani.jpg']
    ];
    
    $stmt = $conn->prepare("INSERT INTO dishes (name, price, category_id, image) VALUES (?, ?, 6, ?)");
    
    foreach ($meat_dishes as $dish) {
        $stmt->bind_param("sds", $dish[0], $dish[1], $dish[2]);
        if ($stmt->execute()) {
            echo "<p>✅ Added: {$dish[0]} - ৳{$dish[1]}</p>";
        } else {
            echo "<p>❌ Failed to add: {$dish[0]} - " . $stmt->error . "</p>";
        }
    }
    
    $stmt->close();
    
    echo "<h3>🎉 Meat category created successfully!</h3>";
    echo "<p><strong>New Bengali Meat Dishes Added:</strong></p>";
    echo "<ul>";
    foreach ($meat_dishes as $dish) {
        echo "<li><strong>{$dish[0]}</strong> - ৳{$dish[1]}</li>";
    }
    echo "</ul>";
    
    echo "<h4>🥩 Beef Dishes (Gorur Mangsho):</h4>";
    echo "<ul>";
    echo "<li><strong>Gorur Mangsho</strong> - Traditional beef curry with spices</li>";
    echo "<li><strong>Gorur Bhuna</strong> - Dry beef preparation with onions</li>";
    echo "<li><strong>Gorur Rezala</strong> - Rich beef curry with yogurt</li>";
    echo "<li><strong>Gorur Korma</strong> - Creamy beef curry with nuts</li>";
    echo "</ul>";
    
    echo "<h4>🍗 Chicken Dishes (Murgir Mangsho):</h4>";
    echo "<ul>";
    echo "<li><strong>Murgir Mangsho</strong> - Traditional chicken curry</li>";
    echo "<li><strong>Murgir Jhol</strong> - Light chicken curry with vegetables</li>";
    echo "<li><strong>Murgir Bhuna</strong> - Dry chicken preparation</li>";
    echo "<li><strong>Murgir Rezala</strong> - Rich chicken curry with yogurt</li>";
    echo "<li><strong>Murgir Korma</strong> - Creamy chicken curry</li>";
    echo "<li><strong>Murgir Biryani</strong> - Fragrant chicken biryani</li>";
    echo "</ul>";
    
    echo "<h4>🐐 Mutton Dishes (Khashir Mangsho):</h4>";
    echo "<ul>";
    echo "<li><strong>Khashir Mangsho</strong> - Traditional mutton curry</li>";
    echo "<li><strong>Khashir Bhuna</strong> - Dry mutton preparation</li>";
    echo "<li><strong>Khashir Rezala</strong> - Rich mutton curry with yogurt</li>";
    echo "</ul>";
    
    echo "<p><strong>Note:</strong> These are authentic Bengali meat preparations with rich spices and traditional cooking methods.</p>";
    
    echo "<p><a href='category-foods.php?category_id=6' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>View Meat Dishes</a></p>";
    echo "<p><a href='foods.php' style='background: #2f3542; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>View All Foods</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?> 