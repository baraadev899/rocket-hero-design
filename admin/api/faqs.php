
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

// Get all FAQs
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM faqs ORDER BY order_index ASC";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $faqs = [];
        while ($row = $result->fetch_assoc()) {
            $faqs[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'data' => $faqs
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'data' => []
        ]);
    }
    exit();
}

// Close database connection
$conn->close();
?>
