<?php
// Database Setup Script for Ghoroa Khabar
// Run this file once to set up your database

echo "<h2>Ghoroa Khabar Database Setup</h2>";

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";

try {
    // Create connection without database
    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    echo "<p>✅ Connected to MySQL server successfully</p>";

    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS ghoroa_khabar";
    if ($conn->query($sql) === TRUE) {
        echo "<p>✅ Database 'ghoroa_khabar' created successfully</p>";
    } else {
        echo "<p>⚠️ Database creation: " . $conn->error . "</p>";
    }

    // Select the database
    $conn->select_db("ghoroa_khabar");

    // Drop existing tables in correct order (child tables first)
    $drop_tables = ["payments", "orders", "dishes", "categories", "users"];
    foreach ($drop_tables as $table) {
        $conn->query("SET FOREIGN_KEY_CHECKS = 0");
        $conn->query("DROP TABLE IF EXISTS $table");
        $conn->query("SET FOREIGN_KEY_CHECKS = 1");
    }
    echo "<p>✅ Existing tables dropped successfully</p>";

    // Create tables
    $tables = [
        "users" => "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('customer', 'admin') DEFAULT 'customer',
            contact VARCHAR(20),
            address TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_email (email),
            INDEX idx_role (role)
        )",

        "categories" => "CREATE TABLE categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            image VARCHAR(255),
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_active (is_active)
        )",

        "dishes" => "CREATE TABLE dishes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            category_id INT,
            image VARCHAR(255),
            is_available BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_category (category_id),
            INDEX idx_available (is_available),
            INDEX idx_name (name)
        )",

        "orders" => "CREATE TABLE orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            dish_id INT NOT NULL,
            quantity INT NOT NULL DEFAULT 1,
            total_price DECIMAL(10,2) NOT NULL,
            order_status ENUM('pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
            payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
            order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            delivery_address TEXT,
            special_instructions TEXT,
            INDEX idx_user (user_id),
            INDEX idx_dish (dish_id),
            INDEX idx_status (order_status),
            INDEX idx_payment_status (payment_status),
            INDEX idx_order_date (order_date)
        )",

        "payments" => "CREATE TABLE payments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            payment_method ENUM('cash', 'bkash', 'online', 'card') NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            transaction_id VARCHAR(100),
            payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
            payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_order (order_id),
            INDEX idx_status (payment_status),
            INDEX idx_transaction (transaction_id)
        )"
    ];

    foreach ($tables as $table_name => $sql) {
        if ($conn->query($sql) === TRUE) {
            echo "<p>✅ Table '$table_name' created successfully</p>";
        } else {
            echo "<p>⚠️ Table '$table_name' creation: " . $conn->error . "</p>";
        }
    }

    // Insert sample data
    $sample_data = [
        "INSERT INTO categories (id, name, description) VALUES
        (1, 'Fish Dishes', 'Fresh Bengali fish preparations'),
        (2, 'Rice & Breads', 'Rice dishes and breads'),
        (3, 'Side Dishes', 'Vegetables and accompaniments'),
        (4, 'Meat Dishes', 'Fresh meat preparations'),
        (5, 'Desserts', 'Sweet Bengali treats')",

        "INSERT INTO dishes (name, price, category_id, image) VALUES
        ('Ilish Fry', 450.00, 1, 'ilish-mach.jpg'),
        ('Rui Macher Jhol', 220.00, 1, 'rui-macher-jhol.webp'),
        ('Katla Macher Jhol', 200.00, 1, 'katla-mach.jpg'),
        ('Pabda Macher Jhol', 180.00, 1, 'pabda-mach.jpg'),
        ('Tengra Macher Jhol', 160.00, 1, 'tengra-mach.jpg'),
        ('Chingri Macher Malai Curry', 350.00, 1, 'chingri-malai.jpg'),
        ('Koi Macher Jhol', 190.00, 1, 'koi-mach.jpg'),
        ('Bhetki Macher Paturi', 280.00, 1, 'bhetki-paturi.jpg'),
        ('Bhuna Khichuri', 150.00, 2, 'khichuri.webp'),
        ('Plain Rice', 40.00, 2, 'rice.jpg'),
        ('Vorta Sampler', 150.00, 3, 'vorta.jpg'),
        ('Gorur Mangsho', 300.00, 4, 'gorur-mangsho.jpg'),
        ('Murgir Mangsho', 200.00, 4, 'murgir-mangsho.jpg')",

        "INSERT INTO users (name, email, password, role, contact, address) VALUES
        ('Admin User', 'admin@ghoroa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '01712345678', 'Dhaka, Bangladesh'),
        ('Customer User', 'customer@ghoroa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '01787654321', 'Sylhet, Bangladesh')"
    ];

    foreach ($sample_data as $sql) {
        if ($conn->query($sql) === TRUE) {
            echo "<p>✅ Sample data inserted successfully</p>";
        } else {
            echo "<p>⚠️ Sample data insertion: " . $conn->error . "</p>";
        }
    }

    echo "<h3>🎉 Database setup completed!</h3>";
    echo "<p><strong>Default Users:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> admin@ghoroa.com / admin123</li>";
    echo "<li><strong>Customer:</strong> customer@ghoroa.com / customer123</li>";
    echo "</ul>";
    echo "<p><a href='home.html' style='background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Go to Homepage</a></p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure XAMPP is running and MySQL service is started.</p>";
}

$conn->close();
