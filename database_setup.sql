-- Sprint 1: Salon Management System Database Setup
-- Run this file if you prefer manual database setup

-- Create Database
CREATE DATABASE IF NOT EXISTS salon_management;
USE salon_management;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    role ENUM('receptionist', 'admin') DEFAULT 'receptionist',
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Staff Table
CREATE TABLE IF NOT EXISTS staff (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    role ENUM('Barber', 'Stylist', 'Admin') NOT NULL,
    specialization VARCHAR(255) DEFAULT NULL,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Appointments Table
CREATE TABLE IF NOT EXISTS appointments (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(255) DEFAULT NULL,
    service_type VARCHAR(255) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    price DECIMAL(10,2) DEFAULT NULL,
    queue_number INT(11) DEFAULT NULL,
    staff_id INT(11) UNSIGNED DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Service Prices Table
CREATE TABLE IF NOT EXISTS service_prices (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(255) NOT NULL UNIQUE,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    description TEXT DEFAULT NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default service prices
INSERT INTO service_prices (service_name, price, description, created_at, updated_at) VALUES
('Haircut', 150.00, 'Basic haircut and styling', NOW(), NOW()),
('Hair Coloring', 1500.00, 'Full hair coloring service', NOW(), NOW()),
('Hair Styling', 300.00, 'Professional hair styling', NOW(), NOW()),
('Hair Treatment', 500.00, 'Hair treatment and care', NOW(), NOW()),
('Manicure', 200.00, 'Basic manicure service', NOW(), NOW()),
('Pedicure', 250.00, 'Basic pedicure service', NOW(), NOW()),
('Facial', 800.00, 'Professional facial treatment', NOW(), NOW()),
('Makeup', 1000.00, 'Professional makeup service', NOW(), NOW());

-- Insert Default Users
INSERT INTO users (username, password, full_name, role, created_at, updated_at) VALUES
('receptionist', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Receptionist User', 'receptionist', NOW(), NOW()),
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin', NOW(), NOW());

-- Note: Default password for both users is 'password'
-- For receptionist123 and admin123, run the seeder instead: php spark db:seed UserSeeder

-- Sample Appointments (Optional)
INSERT INTO appointments (customer_name, customer_phone, customer_email, service_type, appointment_date, appointment_time, status, price, queue_number, notes, created_at, updated_at) VALUES
('Maria Santos', '09171234567', 'maria@email.com', 'Haircut', CURDATE() + INTERVAL 1 DAY, '10:00:00', 'pending', 150.00, 1, 'First time customer', NOW(), NOW()),
('Juan Dela Cruz', '09181234567', NULL, 'Hair Coloring', CURDATE() + INTERVAL 2 DAY, '14:00:00', 'confirmed', 1500.00, 1, 'Prefers brown color', NOW(), NOW()),
('Ana Reyes', '09191234567', 'ana@email.com', 'Manicure', CURDATE() + INTERVAL 1 DAY, '15:30:00', 'pending', 200.00, 2, NULL, NOW(), NOW());

SELECT 'Database setup complete!' AS message;
