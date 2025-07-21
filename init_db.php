<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Create connection without selecting a database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS blood_bank");
    echo "Database created successfully<br>";

    // Select the database
    $pdo->exec("USE blood_bank");

    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        blood_group VARCHAR(3) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        address TEXT NOT NULL,
        city VARCHAR(50) NOT NULL,
        state VARCHAR(50) NOT NULL,
        is_donor BOOLEAN DEFAULT FALSE,
        is_admin BOOLEAN DEFAULT FALSE,
        is_verified BOOLEAN DEFAULT FALSE,
        verification_token VARCHAR(64),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "Users table created successfully<br>";

    // Create blood_requests table
    $pdo->exec("CREATE TABLE IF NOT EXISTS blood_requests (
        id INT PRIMARY KEY AUTO_INCREMENT,
        patient_name VARCHAR(100) NOT NULL,
        blood_group VARCHAR(3) NOT NULL,
        hospital_name VARCHAR(100) NOT NULL,
        hospital_address TEXT NOT NULL,
        city VARCHAR(50) NOT NULL,
        state VARCHAR(50) NOT NULL,
        contact_person VARCHAR(100) NOT NULL,
        contact_number VARCHAR(15) NOT NULL,
        required_date DATE NOT NULL,
        status ENUM('pending', 'fulfilled', 'cancelled') DEFAULT 'pending',
        created_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id)
    )");
    echo "Blood requests table created successfully<br>";

    // Create donations table
    $pdo->exec("CREATE TABLE IF NOT EXISTS donations (
        id INT PRIMARY KEY AUTO_INCREMENT,
        donor_id INT NOT NULL,
        blood_group VARCHAR(3) NOT NULL,
        donation_date DATE NOT NULL,
        next_donation_date DATE NOT NULL,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (donor_id) REFERENCES users(id)
    )");
    echo "Donations table created successfully<br>";

    echo "Database setup completed successfully!";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 