<?php
include 'db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Ghoroa Khabar - Authentic Bengali Food</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .hero-section h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        .hero-section p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta-buttons {
            margin-top: 30px;
        }
        
        .cta-buttons a {
            display: inline-block;
            margin: 0 15px;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .cta-primary {
            background: #ff6b6b;
            color: white;
        }
        
        .cta-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .cta-primary:hover {
            background: #ff5252;
            transform: translateY(-2px);
        }
        
        .cta-secondary:hover {
            background: white;
            color: #333;
        }
        
        .features-section {
            padding: 80px 0;
            background: #f9f9f9;
        }
        
        .feature-box {
            text-align: center;
            padding: 30px 20px;
            margin-bottom: 30px;
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #ff6b6b;
        }
        
        .feature-box h3 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .feature-box p {
            color: #666;
            line-height: 1.6;
        }
        
        .popular-dishes {
            padding: 80px 0;
        }
        
        .dish-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }
        
        .dish-card:hover {
            transform: translateY(-5px);
        }
        
        .dish-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .dish-card-content {
            padding: 20px;
        }
        
        .dish-card h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        
        .dish-price {
            color: #ff6b6b;
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .dish-card p {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .order-btn {
            background: #ff6b6b;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 25px;
            display: inline-block;
            transition: background 0.3s ease;
        }
        
        .order-btn:hover {
            background: #ff5252;
        }
        
        .special-offers {
            padding: 80px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .offer-card {
            background: rgba(255,255,255,0.1);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
        }
        
        .offer-card h3 {
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        
        .testimonials {
            padding: 80px 0;
            background: #f9f9f9;
        }
        
        .testimonial-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .testimonial-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background: #ff6b6b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }
        
        .stats-section {
            padding: 60px 0;
            background: #2f3542;
            color: white;
        }
        
        .stat-item {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: #ff6b6b;
            margin-bottom: 10px;
        }
        
        .admin-welcome {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
        }
        
        .admin-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .admin-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 25px;
            border: 2px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
        }
        
        .admin-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <section class="navbar">
        <div class="container">
            <div class="logo" style="display: flex; align-items: center;">
                <a href="home.php" title="Ghoroa Khabar" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="images/logo.png" alt="Ghoroa Khabar Logo" class="img-responsive" style="width: 60px; height: auto; margin-right: 10px;">
                    <span style="font-size: 1.8rem; font-weight: bold; color: #2f3542;">Ghoroa Khabar</span>
                </a>
            </div>

            <div class="menu text-right">
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="categories.html">Categories</a></li>
                    <li><a href="foods.php">Foods</a></li>
                    <?php if (is_logged_in()): ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">Log Out (<?php echo htmlspecialchars($_SESSION['name']); ?>)</a></li>
                    <?php else: ?>
                        <li><a href="login.html">Log In</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>

    <!-- Admin Welcome Section (only for admin users) -->
    <?php if (is_logged_in() && $_SESSION['role'] === 'admin'): ?>
    <div class="container">
        <div class="admin-welcome">
            <h2>👋 Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
            <p>You are logged in as an Administrator. Access your admin tools below:</p>
            <div class="admin-actions">
                <a href="admin_dashboard.php" class="admin-btn">📊 Admin Dashboard</a>
                <a href="admin_delivery.php" class="admin-btn">🚚 Delivery Management</a>
                <a href="setup_delivery_system.php" class="admin-btn">⚙️ Setup Delivery</a>
                <a href="check_delivery_status.php" class="admin-btn">🔍 Check Status</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Welcome to Ghoroa Khabar</h1>
            <p>Experience the authentic taste of Bengali cuisine, crafted with love and traditional recipes passed down through generations. From the royal kitchens of Bengal to your dining table.</p>
            <div class="cta-buttons">
                <a href="foods.php" class="cta-primary">Order Now</a>
                <a href="categories.html" class="cta-secondary">Explore Menu</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="text-center" style="margin-bottom: 50px; font-size: 2.5rem; color: #333;">Why Choose Ghoroa Khabar?</h2>
            
            <div class="row">
                <div class="col-3">
                    <div class="feature-box">
                        <div class="feature-icon">🍽️</div>
                        <h3>Authentic Recipes</h3>
                        <p>Every dish is prepared using traditional Bengali recipes and cooking methods, ensuring the authentic taste of Bengal.</p>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="feature-box">
                        <div class="feature-icon">🌿</div>
                        <h3>Fresh Ingredients</h3>
                        <p>We use only the freshest ingredients and spices, sourced locally to maintain the highest quality standards.</p>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="feature-box">
                        <div class="feature-icon">⚡</div>
                        <h3>Fast Delivery</h3>
                        <p>Quick and reliable delivery service ensures your food reaches you hot and fresh, just like home-cooked meals.</p>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="feature-box">
                        <div class="feature-icon">👨‍🍳</div>
                        <h3>Expert Chefs</h3>
                        <p>Our experienced chefs specialize in Bengali cuisine, bringing years of expertise to create perfect dishes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Dishes Section -->
    <section class="popular-dishes">
        <div class="container">
            <h2 class="text-center" style="margin-bottom: 50px; font-size: 2.5rem; color: #333;">Our Most Popular Dishes</h2>
            
            <div class="row">
                <div class="col-3">
                    <div class="dish-card">
                        <img src="images/ilish-mach.jpg" alt="Ilish Fry">
                        <div class="dish-card-content">
                            <h4>Ilish Fry</h4>
                            <div class="dish-price">৳450</div>
                            <p>The king of fish, crispy fried hilsa with traditional spices.</p>
                            <a href="order.php?dish_id=1" class="order-btn">Order Now</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="dish-card">
                        <img src="images/gorur-mangsho.jpg" alt="Gorur Mangsho">
                        <div class="dish-card-content">
                            <h4>Gorur Mangsho</h4>
                            <div class="dish-price">৳350</div>
                            <p>Traditional beef curry with rich spices and authentic Bengali taste.</p>
                            <a href="order.php?dish_id=15" class="order-btn">Order Now</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="dish-card">
                        <img src="images/khichuri.jpg" alt="Khichuri">
                        <div class="dish-card-content">
                            <h4>Khichuri</h4>
                            <div class="dish-price">৳120</div>
                            <p>Comforting rice and lentil dish, perfect for rainy days.</p>
                            <a href="order.php?dish_id=8" class="order-btn">Order Now</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="dish-card">
                        <img src="images/alu-vorta.jpg" alt="Alu Vorta">
                        <div class="dish-card-content">
                            <h4>Alu Vorta</h4>
                            <div class="dish-price">৳80</div>
                            <p>Mashed potato with mustard oil and spices, a classic side dish.</p>
                            <a href="order.php?dish_id=12" class="order-btn">Order Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Special Offers Section -->
    <section class="special-offers">
        <div class="container">
            <h2 class="text-center" style="margin-bottom: 50px; font-size: 2.5rem;">Special Offers</h2>
            
            <div class="row">
                <div class="col-3">
                    <div class="offer-card">
                        <h3>🎉 New Customer Discount</h3>
                        <p>Get 20% off on your first order! Use code: WELCOME20</p>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="offer-card">
                        <h3>🚚 Free Delivery</h3>
                        <p>Free delivery on orders above ৳500. Valid in selected areas.</p>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="offer-card">
                        <h3>👥 Family Pack</h3>
                        <p>Order for 4+ people and get 15% discount on total bill.</p>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="offer-card">
                        <h3>🎂 Birthday Special</h3>
                        <p>Celebrate your birthday with us! Get a free dessert on your special day.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <h2 class="text-center" style="margin-bottom: 50px; font-size: 2.5rem; color: #333;">What Our Customers Say</h2>
            
            <div class="row">
                <div class="col-3">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">👨</div>
                        <h4>Ahmed Khan</h4>
                        <p>"The Ilish Fry is absolutely amazing! Tastes just like my grandmother used to make. Highly recommended!"</p>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">👩</div>
                        <h4>Fatima Begum</h4>
                        <p>"Fast delivery and authentic taste. The Khichuri is perfect for our family dinner. Will order again!"</p>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">👨‍🦳</div>
                        <h4>Abdul Rahman</h4>
                        <p>"Been living abroad for years. This food brings back all the memories of home. Thank you Ghoroa Khabar!"</p>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">👩‍🦱</div>
                        <h4>Nusrat Jahan</h4>
                        <p>"The quality and taste are outstanding. The delivery was on time and the food was piping hot. Perfect!"</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <div class="stat-item">
                        <div class="stat-number">5000+</div>
                        <p>Happy Customers</p>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <p>Authentic Dishes</p>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="stat-item">
                        <div class="stat-number">25</div>
                        <p>Minutes Delivery</p>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="stat-item">
                        <div class="stat-number">4.8</div>
                        <p>Star Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <section class="footer">
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <h3>Ghoroa Khabar</h3>
                    <p>Authentic Bengali cuisine delivered to your doorstep. Experience the taste of tradition.</p>
                </div>
                
                <div class="col-3">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="home.php">Home</a></li>
                        <li><a href="categories.html">Menu</a></li>
                        <li><a href="foods.php">All Foods</a></li>
                        <li><a href="login.html">Login</a></li>
                    </ul>
                </div>
                
                <div class="col-3">
                    <h3>Contact Info</h3>
                    <p>📞 +880 1712-345678</p>
                    <p>📧 info@ghoroakhabar.com</p>
                    <p>📍 Sylhet, Bangladesh</p>
                </div>
                
                <div class="col-3">
                    <h3>Opening Hours</h3>
                    <p>Monday - Friday: 11:00 AM - 10:00 PM</p>
                    <p>Saturday - Sunday: 12:00 PM - 11:00 PM</p>
                </div>
            </div>
            
            <div class="text-center" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
                <p>&copy; 2025 Ghoroa Khabar. All rights reserved.</p>
            </div>
        </div>
    </section>

</body>
</html> 