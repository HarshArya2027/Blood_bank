<?php
require_once 'config.php';

echo "<h2>Database Connection Test</h2>";

try {
    // Test database connection
    $pdo->query("SELECT 1");
    echo "<div style='color: green;'>✓ Database connection successful!</div><br>";
    
    // Check if database exists
    $stmt = $pdo->query("SELECT DATABASE()");
    $dbName = $stmt->fetchColumn();
    echo "Current database: " . $dbName . "<br><br>";
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<div style='color: green;'>✓ Users table exists!</div><br>";
        
        // Show table structure
        echo "<h3>Users Table Structure:</h3>";
        $stmt = $pdo->query("DESCRIBE users");
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
        
        // Check for sample data
        echo "<h3>Sample Users:</h3>";
        $stmt = $pdo->query("SELECT id, name, email, is_verified, is_admin FROM users LIMIT 5");
        if ($stmt->rowCount() > 0) {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Verified</th><th>Admin</th></tr>";
            while ($row = $stmt->fetch()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . ($row['is_verified'] ? 'Yes' : 'No') . "</td>";
                echo "<td>" . ($row['is_admin'] ? 'Yes' : 'No') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div style='color: orange;'>No users found in the database. Please register a user first.</div>";
        }
    } else {
        echo "<div style='color: red;'>❌ Users table does not exist!</div>";
        echo "<p>Please run the following SQL to create the users table:</p>";
        echo "<pre>
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    blood_group VARCHAR(5),
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    is_donor BOOLEAN DEFAULT FALSE,
    is_verified BOOLEAN DEFAULT FALSE,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
        </pre>";
    }
} catch(PDOException $e) {
    echo "<div style='color: red;'>❌ Error: " . $e->getMessage() . "</div>";
    echo "<p>Please check your database configuration in config.php:</p>";
    echo "<pre>
\$host = 'localhost';
\$dbname = 'blood_bank';
\$username = 'root';
\$password = '';
    </pre>";
}
?> 