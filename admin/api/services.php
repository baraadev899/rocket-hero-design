
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

// Get all services
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM services ORDER BY order_index ASC";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $services = [];
        while ($row = $result->fetch_assoc()) {
            // Handle image path if exists
            if (!empty($row['image'])) {
                $row['image'] = strpos($row['image'], 'http') === 0 ? $row['image'] : '../../' . $row['image'];
            }
            $services[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'data' => $services
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
