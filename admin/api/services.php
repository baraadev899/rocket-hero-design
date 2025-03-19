
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
    $query = "SELECT * FROM services ORDER BY COALESCE(order_index, id) ASC";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $services = [];
        while ($row = $result->fetch_assoc()) {
            // Handle image path if exists
            if (!empty($row['image'])) {
                if (strpos($row['image'], 'http') !== 0 && strpos($row['image'], 'assets/') !== 0) {
                    $row['image'] = 'assets/images/' . $row['image'];
                }
            }
            
            // Ensure icons have 'fa-' prefix
            if (!empty($row['icon']) && strpos($row['icon'], 'fa-') !== 0) {
                $row['icon'] = 'fa-' . $row['icon'];
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

// Create a new service
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($data['title'])) {
        echo json_encode([
            'success' => false,
            'message' => 'عنوان الخدمة مطلوب'
        ]);
        exit();
    }
    
    // Prepare and execute SQL query
    $query = "INSERT INTO services (title, description, icon, image, features, order_index) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    $title = $data['title'];
    $description = $data['description'] ?? '';
    $icon = $data['icon'] ?? 'fa-rocket';
    $image = $data['image'] ?? '';
    $features = $data['features'] ?? '';
    $order_index = $data['order_index'] ?? null;
    
    $stmt->bind_param('sssssi', $title, $description, $icon, $image, $features, $order_index);
    
    if ($stmt->execute()) {
        $service_id = $stmt->insert_id;
        
        echo json_encode([
            'success' => true,
            'message' => 'تم إضافة الخدمة بنجاح',
            'data' => [
                'id' => $service_id,
                'title' => $title,
                'description' => $description,
                'icon' => $icon,
                'image' => $image,
                'features' => $features,
                'order_index' => $order_index
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'حدث خطأ أثناء إضافة الخدمة: ' . $stmt->error
        ]);
    }
    
    $stmt->close();
    exit();
}

// Update an existing service
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get service ID from URL
    $url_parts = explode('/', $_SERVER['REQUEST_URI']);
    $service_id = end($url_parts);
    
    if (!is_numeric($service_id)) {
        echo json_encode([
            'success' => false,
            'message' => 'معرف الخدمة غير صالح'
        ]);
        exit();
    }
    
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($data['title'])) {
        echo json_encode([
            'success' => false,
            'message' => 'عنوان الخدمة مطلوب'
        ]);
        exit();
    }
    
    // Prepare and execute SQL query
    $query = "UPDATE services SET title = ?, description = ?, icon = ?, image = ?, features = ?, order_index = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    $title = $data['title'];
    $description = $data['description'] ?? '';
    $icon = $data['icon'] ?? 'fa-rocket';
    $image = $data['image'] ?? '';
    $features = $data['features'] ?? '';
    $order_index = $data['order_index'] ?? null;
    
    $stmt->bind_param('sssssii', $title, $description, $icon, $image, $features, $order_index, $service_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'تم تحديث الخدمة بنجاح',
            'data' => [
                'id' => $service_id,
                'title' => $title,
                'description' => $description,
                'icon' => $icon,
                'image' => $image,
                'features' => $features,
                'order_index' => $order_index
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'حدث خطأ أثناء تحديث الخدمة: ' . $stmt->error
        ]);
    }
    
    $stmt->close();
    exit();
}

// Delete a service
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get service ID from URL
    $url_parts = explode('/', $_SERVER['REQUEST_URI']);
    $service_id = end($url_parts);
    
    if (!is_numeric($service_id)) {
        echo json_encode([
            'success' => false,
            'message' => 'معرف الخدمة غير صالح'
        ]);
        exit();
    }
    
    // Prepare and execute SQL query
    $query = "DELETE FROM services WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $service_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'تم حذف الخدمة بنجاح'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'حدث خطأ أثناء حذف الخدمة: ' . $stmt->error
        ]);
    }
    
    $stmt->close();
    exit();
}

// Close database connection
$conn->close();
?>
