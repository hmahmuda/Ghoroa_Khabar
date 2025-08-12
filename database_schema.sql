-- Ghoroa Khabar Database Schema
-- Run this in phpMyAdmin or MySQL command line to create the database structure

-- Creating databases
CREATE DATABASE  ghoroa_khabar;
USE ghoroa_khabar;

-- Users table
CREATE TABLE users (
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
);

-- Categories table
CREATE TABLE  categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_active (is_active)
);

-- Dishes table
CREATE TABLE  dishes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_category (category_id),
    INDEX idx_available (is_available),
    INDEX idx_name (name)
);

-- Orders table
CREATE TABLE IF  orders (
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
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (dish_id) REFERENCES dishes(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_dish (dish_id),
    INDEX idx_status (order_status),
    INDEX idx_payment_status (payment_status),
    INDEX idx_order_date (order_date)
);

-- Payments table
CREATE TABLE  payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method ENUM('cash', 'bkash', 'online', 'card') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    transaction_id VARCHAR(100),
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX idx_order (order_id),
    INDEX idx_status (payment_status),
    INDEX idx_transaction (transaction_id)
);

-- Sample data for testing

-- Insert sample categories
INSERT INTO categories (name, description) VALUES
('Rice Dishes', 'Delicious rice-based dishes'),
('Meat Dishes', 'Fresh meat preparations'),
('Fish Dishes', 'Fresh fish dishes'),
('Vegetables', 'Healthy vegetable dishes'),
('Desserts', 'Sweet treats');

-- Insert sample dishes
INSERT INTO dishes (name, description, price, category_id) VALUES
('Khichuri', 'Traditional rice and lentil dish', 120.00, 1),
('Biryani', 'Aromatic rice with meat', 250.00, 1),
('Gorur Mangsho', 'Beef curry', 300.00, 2),
('Murgir Mangsho', 'Chicken curry', 200.00, 2),
('Ilish Mach', 'Hilsa fish curry', 400.00, 3),
('Rui Macher Jhol', 'Rohu fish curry', 180.00, 3),
('Vorta', 'Mashed vegetables', 80.00, 4),
('Rice', 'Plain steamed rice', 30.00, 1);

-- Insert sample admin user (password: admin123)
INSERT INTO users (name, email, password, role, contact, address) VALUES
('Admin User', 'admin@ghoroa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '01712345678', 'Dhaka, Bangladesh');

-- Insert sample customer user (password: customer123)
INSERT INTO users (name, email, password, role, contact, address) VALUES
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '01787654321', 'Dhaka, Bangladesh'); 