-- Delivery System Database Tables

-- Delivery Persons Table
CREATE TABLE delivery_persons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    vehicle_number VARCHAR(20),
    vehicle_type ENUM('bike', 'car', 'cycle') DEFAULT 'bike',
    is_available BOOLEAN DEFAULT TRUE,
    current_location VARCHAR(255),
    rating DECIMAL(3,2) DEFAULT 5.00,
    total_deliveries INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_available (is_available),
    INDEX idx_rating (rating)
);

-- Delivery Areas Table
CREATE TABLE delivery_areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    area_name VARCHAR(100) NOT NULL,
    postcode VARCHAR(10),
    delivery_fee DECIMAL(10,2) DEFAULT 0.00,
    estimated_time INT DEFAULT 30, -- in minutes
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_active (is_active),
    INDEX idx_postcode (postcode)
);

-- Delivery Assignments Table
CREATE TABLE delivery_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    delivery_person_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estimated_pickup_time TIMESTAMP NULL,
    estimated_delivery_time TIMESTAMP NULL,
    actual_pickup_time TIMESTAMP NULL,
    actual_delivery_time TIMESTAMP NULL,
    status ENUM('assigned', 'picked_up', 'in_transit', 'delivered', 'cancelled') DEFAULT 'assigned',
    delivery_fee DECIMAL(10,2) DEFAULT 0.00,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order (order_id),
    INDEX idx_delivery_person (delivery_person_id),
    INDEX idx_status (status),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (delivery_person_id) REFERENCES delivery_persons(id)
);

-- Delivery Tracking Table
CREATE TABLE delivery_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id INT NOT NULL,
    status ENUM('assigned', 'picked_up', 'in_transit', 'delivered', 'cancelled') NOT NULL,
    location VARCHAR(255),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_assignment (assignment_id),
    INDEX idx_status (status),
    FOREIGN KEY (assignment_id) REFERENCES delivery_assignments(id) ON DELETE CASCADE
);

-- Sample Data for Delivery System
INSERT INTO delivery_persons (name, phone, email, vehicle_number, vehicle_type, current_location) VALUES
('Rahim Ali', '01712345678', 'rahim@ghoroa.com', 'SYL-1234', 'bike', 'Zindabazar, Sylhet'),
('Karim Uddin', '01787654321', 'karim@ghoroa.com', 'SYL-5678', 'bike', 'Bondor, Sylhet'),
('Fatima Begum', '01812345678', 'fatima@ghoroa.com', 'SYL-9012', 'car', 'Subhanighat, Sylhet'),
('Salam Mia', '01987654321', 'salam@ghoroa.com', 'SYL-3456', 'bike', 'Tilagor, Sylhet'),
('Abdul Kader', '01612345678', 'kader@ghoroa.com', 'SYL-7890', 'bike', 'Mirabazar, Sylhet'),
('Nusrat Jahan', '01587654321', 'nusrat@ghoroa.com', 'SYL-2345', 'bike', 'Kumarpara, Sylhet');

INSERT INTO delivery_areas (area_name, postcode, delivery_fee, estimated_time) VALUES
('Zindabazar', '3100', 40.00, 15),
('Bondor', '3100', 35.00, 12),
('Subhanighat', '3100', 45.00, 18),
('Tilagor', '3100', 30.00, 10),
('Mirabazar', '3100', 50.00, 20),
('Kumarpara', '3100', 35.00, 12),
('Shahjalal Upashahar', '3100', 55.00, 22),
('Airport Road', '3100', 60.00, 25),
('Shibganj', '3100', 40.00, 15),
('Chowhatta', '3100', 30.00, 10),
('Kajalshah', '3100', 35.00, 12),
('Taltala', '3100', 45.00, 18),
('Bagbari', '3100', 40.00, 15),
('Lamabazar', '3100', 50.00, 20),
('Uposhohor', '3100', 55.00, 22),
('Jalalabad', '3100', 65.00, 28),
('Khadimpara', '3100', 70.00, 30),
('Biswanath', '3100', 75.00, 35),
('Golapganj', '3100', 80.00, 40),
('Beanibazar', '3100', 85.00, 45); 