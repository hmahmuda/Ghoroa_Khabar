<?php
include 'db.php';
session_start();

$search = isset($_POST['search']) ? sanitize_input($_POST['search']) : '';
$dishes = [];

if (!empty($search)) {
    // Use prepared statement for search
    $stmt = $conn->prepare("SELECT * FROM dishes WHERE name LIKE ?");
    if ($stmt) {
        $search_term = "%$search%";
        $stmt->bind_param("s", $search_term);
        $stmt->execute();
        $result = $stmt->get_result();
        $dishes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search | Ghoroa Khabar</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navbar -->
<section class="navbar">
    <div class="container">
        <div class="logo">
            <a href="home.html"><img src="images/logo.png" alt="Ghoroa Khabar" class="img-responsive"></a>
        </div>
        <div class="menu text-right">
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="categories.html">Categories</a></li>
                <li><a href="foods.php">Foods</a></li>
                <?php if (is_logged_in()): ?>
                    <li><a href="logout.php">Log Out</a></li>
                <?php else: ?>
                    <li><a href="login.php">Log In</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</section>

<!-- Search Form -->
<section class="food-search text-center">
    <div class="container">
        <h2>Search for Food</h2>
        
        <form method="POST" action="food-search.php" style="max-width: 500px; margin: 0 auto;">
            <div style="display: flex; gap: 10px;">
                <input type="text" name="search" placeholder="Search for food..." 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       style="flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 4px;">
                <button type="submit" style="background-color: #ff6b6b; color: white; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer;">Search</button>
            </div>
        </form>
        
        <?php if (!empty($search)): ?>
            <h3 style="margin-top: 30px;">Foods matching: <span class="text-white">"<?php echo htmlspecialchars($search); ?>"</span></h3>
        <?php endif; ?>
    </div>
</section>

<!-- Results -->
<section class="food-menu">
    <div class="container">
        <?php if (!empty($search)): ?>
            <?php if (!empty($dishes)): ?>
                <h2 class="text-center">Search Results</h2>
                <div class="food-menu-box-container">
                    <?php foreach ($dishes as $dish): ?>
                        <div class="food-menu-box">
                            <div class="food-menu-img">
                                <?php 
                                $image_path = "images/" . strtolower(str_replace(' ', '-', $dish['name'])) . ".jpg";
                                if (file_exists($image_path)) {
                                    echo "<img src='$image_path' alt='" . htmlspecialchars($dish['name']) . "' class='img-responsive img-curve'>";
                                } else {
                                    echo "<img src='images/default-food.jpg' alt='" . htmlspecialchars($dish['name']) . "' class='img-responsive img-curve'>";
                                }
                                ?>
                            </div>
                            <div class="food-menu-desc">
                                <h4><?php echo htmlspecialchars($dish['name']); ?></h4>
                                <p class="food-price">৳<?php echo number_format($dish['price'], 2); ?></p>
                                <p class="food-detail">Delicious homemade dish.</p>
                                <br>
                                <?php if (is_logged_in()): ?>
                                    <a href="order.php?dish_id=<?php echo $dish['id']; ?>" class="btn btn-primary">Order Now</a>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-primary">Login to Order</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 50px;">
                    <h3>No food found</h3>
                    <p>No food items match "<?php echo htmlspecialchars($search); ?>"</p>
                    <a href="foods.php" style="background-color: #ff6b6b; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px;">View All Foods</a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 50px;">
                <h3>Search for Food</h3>
                <p>Enter a food name in the search box above to find delicious dishes.</p>
                <a href="foods.php" style="background-color: #ff6b6b; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px;">View All Foods</a>
            </div>
        <?php endif; ?>

        <div class="clearfix"></div>
    </div>
</section>

<!-- Footer -->
<section class="social">
    <div class="container text-center">
        <ul>
            <li><a href="#"><img src="https://img.icons8.com/fluent/50/000000/facebook-new.png"/></a></li>
            <li><a href="#"><img src="https://img.icons8.com/fluent/48/000000/instagram-new.png"/></a></li>
            <li><a href="#"><img src="https://img.icons8.com/fluent/48/000000/twitter.png"/></a></li>
        </ul>
    </div>
</section>

<section class="footer">
    <div class="container text-center">
        <p>All rights reserved. Designed By <a href="#">You</a></p>
    </div>
</section>

</body>
</html>
