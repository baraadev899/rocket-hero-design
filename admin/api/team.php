
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

// Get all team members
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM team ORDER BY order_index ASC";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $team = [];
        while ($row = $result->fetch_assoc()) {
            // Handle image path
            $row['image'] = strpos($row['image'], 'http') === 0 ? $row['image'] : '../../' . $row['image'];
            $team[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'data' => $team
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
