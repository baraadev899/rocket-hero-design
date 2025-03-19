
<?php
// Start session
session_start();

// Check if already authenticated
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Allowing access to this debug page without login for testing purposes
    // In production, uncomment below lines to restrict access
    /*
    header('Location: login.php');
    exit;
    */
}

// Database connection
require_once 'config/db.php';

echo "<h1>Admin System Debug</h1>";

// Check database connection
echo "<h2>Database Connection</h2>";
if ($conn && $conn->ping()) {
    echo "<p style='color:green'>✅ Database connection is working</p>";
} else {
    echo "<p style='color:red'>❌ Database connection failed: " . $conn->error . "</p>";
}

// Check admins table
echo "<h2>Admins Table</h2>";
$query = "SHOW TABLES LIKE 'admins'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<p style='color:green'>✅ Admins table exists</p>";
    
    // Check admin accounts
    $query = "SELECT id, name, username, email FROM admins";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        echo "<p>Found " . $result->num_rows . " admin accounts:</p>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p style='color:red'>❌ No admin accounts found. Adding default admin account...</p>";
        
        // Add default admin user if none exists
        $name = "المسؤول الرئيسي";
        $username = "admin";
        $email = "admin@rocket-agency.com";
        $password = password_hash("admin123", PASSWORD_DEFAULT);
        
        $query = "INSERT INTO admins (name, username, password, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $username, $password, $email);
        
        if ($stmt->execute()) {
            echo "<p style='color:green'>✅ Default admin account created successfully.</p>";
            echo "<p>Username: admin<br>Password: admin123</p>";
        } else {
            echo "<p style='color:red'>❌ Failed to create default admin: " . $stmt->error . "</p>";
        }
    }
} else {
    echo "<p style='color:red'>❌ Admins table doesn't exist. Creating table...</p>";
    
    // Create admins table
    $query = "CREATE TABLE `admins` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(100) NOT NULL,
              `username` varchar(50) NOT NULL,
              `password` varchar(255) NOT NULL,
              `email` varchar(100) NOT NULL,
              `last_login` datetime DEFAULT NULL,
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              UNIQUE KEY `username` (`username`),
              UNIQUE KEY `email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($query) === TRUE) {
        echo "<p style='color:green'>✅ Admins table created successfully.</p>";
        
        // Add default admin user
        $name = "المسؤول الرئيسي";
        $username = "admin";
        $email = "admin@rocket-agency.com";
        $password = password_hash("admin123", PASSWORD_DEFAULT);
        
        $query = "INSERT INTO admins (name, username, password, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $username, $password, $email);
        
        if ($stmt->execute()) {
            echo "<p style='color:green'>✅ Default admin account created successfully.</p>";
            echo "<p>Username: admin<br>Password: admin123</p>";
        } else {
            echo "<p style='color:red'>❌ Failed to create default admin: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p style='color:red'>❌ Error creating table: " . $conn->error . "</p>";
    }
}

// Check session
echo "<h2>Session Information</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Check POST data if login was attempted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>POST Data from Login Attempt</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
}
?>

<p><a href="login.php">Back to Login</a></p>
