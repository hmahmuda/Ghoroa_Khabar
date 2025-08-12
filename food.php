<?php
include 'db.php';
session_start();

// Fetch all dishes using prepared statement
$stmt = $conn->prepare("SELECT * FROM dishes WHERE is_available = 1 ORDER BY name");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $dishes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $dishes = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>All Foods | Ghoroa Khabar</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<!-- Navbar Section -->
<section class="navbar">
    <div class="container">
        <div class="logo" style="display: flex; align-items: center;">
            <a href="home.html" title="Ghoroa Khabar" style="display: flex; align-items: center; text-decoration: none;">
                <img src="images/logo.png" alt="Ghoroa Khabar Logo" class="img-responsive" style="width: 60px; height: auto; margin-right: 10px;">
                <span style="font-size: 1.8rem; font-weight: bold; color: #2f3542;">Ghoroa Khabar</span>
            </a>
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

<!-- Food Menu Section -->
<section class="food-menu">
    <div class="container">
        <h2 class="text-center">All Foods</h2>

        <?php if (!empty($dishes)): ?>
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
                            <p class="food-detail">Delicious homemade food.</p>
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
                <h3>No food available</h3>
                <p>Please check back later for delicious dishes.</p>
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
