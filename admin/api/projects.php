
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

// Get all projects or featured projects
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if we want featured projects only
    $featured = isset($_GET['featured']) ? (bool)$_GET['featured'] : false;
    
    if ($featured) {
        $query = "SELECT * FROM projects WHERE featured = 1 ORDER BY created_at DESC";
    } else {
        $query = "SELECT * FROM projects ORDER BY created_at DESC";
    }
    
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $projects = [];
        while ($row = $result->fetch_assoc()) {
            // Handle image path
            $row['image'] = strpos($row['image'], 'http') === 0 ? $row['image'] : '../../' . $row['image'];
            $projects[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'data' => $projects
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'data' => []
        ]);
    }
    exit();
}

// Get project by ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM projects WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
        // Handle image path
        $project['image'] = strpos($project['image'], 'http') === 0 ? $project['image'] : '../../' . $project['image'];
        
        echo json_encode([
            'success' => true,
            'data' => $project
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'لم يتم العثور على المشروع'
        ]);
    }
    exit();
}

// Close database connection
$conn->close();
?>
