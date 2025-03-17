
<?php
// Allow CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle OPTIONS request for CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include database configuration
require_once '../config/db.php';

// Get settings
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM settings LIMIT 1";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $settings = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'data' => $settings
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'لم يتم العثور على إعدادات'
        ]);
    }
    exit();
}

// Close database connection
$conn->close();
?>
