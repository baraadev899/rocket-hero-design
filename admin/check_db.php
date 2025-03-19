
<?php
// Check database connection
require_once 'config/db.php';

// Simple check to see if DB connection works
if ($conn && $conn->ping()) {
    echo "Database connection successful!";
    
    // Additional checks
    echo "<h3>Checking tables:</h3>";
    $tables = ["admins", "messages", "projects", "services", "settings", "team"];
    
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            $count = $conn->query("SELECT COUNT(*) as count FROM $table")->fetch_assoc()['count'];
            echo "<p>✅ Table '$table' exists and has $count records.</p>";
        } else {
            echo "<p>❌ Table '$table' does not exist!</p>";
        }
    }
} else {
    echo "Database connection failed: " . $conn->connect_error;
}
?>
