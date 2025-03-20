
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
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id'])) {
    // Check if we want featured projects only
    $featured = isset($_GET['featured']) ? (bool)$_GET['featured'] : false;
    
    if ($featured) {
        $query = "SELECT * FROM projects WHERE featured = 1 ORDER BY order_index, created_at DESC";
    } else {
        $query = "SELECT * FROM projects ORDER BY order_index, created_at DESC";
    }
    
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $projects = [];
        while ($row = $result->fetch_assoc()) {
            // Handle image path
            if (strpos($row['image'], 'http') !== 0 && strpos($row['image'], 'assets/') !== 0) {
                $row['image'] = 'assets/uploads/' . $row['image'];
            }
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
        if (strpos($project['image'], 'http') !== 0 && strpos($project['image'], 'assets/') !== 0) {
            $project['image'] = 'assets/uploads/' . $project['image'];
        }
        
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

// Create a new project
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($data['title']) || empty($data['description']) || empty($data['category'])) {
        echo json_encode([
            'success' => false,
            'message' => 'يجب إدخال عنوان المشروع والوصف والتصنيف'
        ]);
        exit();
    }
    
    // Set default order_index if not provided
    if (!isset($data['order_index']) || $data['order_index'] === '') {
        $data['order_index'] = 0;
    }
    
    // Prepare and execute SQL query
    $query = "INSERT INTO projects (title, description, category, client, date, image, link, featured, order_index, meta_title, meta_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    $title = $data['title'];
    $description = $data['description'];
    $category = $data['category'];
    $client = $data['client'] ?? null;
    $date = $data['date'] ?? null;
    $image = $data['image'] ?? null;
    $link = $data['link'] ?? null;
    $featured = isset($data['featured']) ? (int)$data['featured'] : 0;
    $order_index = (int)$data['order_index'];
    $meta_title = $data['meta_title'] ?? null;
    $meta_description = $data['meta_description'] ?? null;
    
    $stmt->bind_param('sssssssiiss', $title, $description, $category, $client, $date, $image, $link, $featured, $order_index, $meta_title, $meta_description);
    
    if ($stmt->execute()) {
        $project_id = $stmt->insert_id;
        
        echo json_encode([
            'success' => true,
            'message' => 'تم إضافة المشروع بنجاح',
            'data' => [
                'id' => $project_id,
                'title' => $title,
                'description' => $description,
                'category' => $category,
                'client' => $client,
                'date' => $date,
                'image' => $image,
                'link' => $link,
                'featured' => $featured,
                'order_index' => $order_index,
                'meta_title' => $meta_title,
                'meta_description' => $meta_description
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'حدث خطأ أثناء إضافة المشروع: ' . $stmt->error
        ]);
    }
    
    $stmt->close();
    exit();
}

// Update an existing project
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get project ID from URL
    $url_parts = explode('/', $_SERVER['REQUEST_URI']);
    $project_id = end($url_parts);
    
    if (!is_numeric($project_id)) {
        echo json_encode([
            'success' => false,
            'message' => 'معرف المشروع غير صالح'
        ]);
        exit();
    }
    
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($data['title']) || empty($data['description']) || empty($data['category'])) {
        echo json_encode([
            'success' => false,
            'message' => 'يجب إدخال عنوان المشروع والوصف والتصنيف'
        ]);
        exit();
    }
    
    // Set default order_index if not provided
    if (!isset($data['order_index']) || $data['order_index'] === '') {
        $data['order_index'] = 0;
    }
    
    // Check if we need to update the image
    $imageSet = false;
    $imageClause = '';
    $imageValue = null;
    
    if (isset($data['image']) && !empty($data['image'])) {
        $imageSet = true;
        $imageClause = ', image = ?';
        $imageValue = $data['image'];
    }
    
    // Prepare and execute SQL query
    $query = "UPDATE projects SET 
              title = ?, 
              description = ?, 
              category = ?, 
              client = ?, 
              date = ?, 
              link = ?, 
              featured = ?,
              order_index = ?,
              meta_title = ?,
              meta_description = ?
              $imageClause 
              WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    
    $title = $data['title'];
    $description = $data['description'];
    $category = $data['category'];
    $client = $data['client'] ?? null;
    $date = $data['date'] ?? null;
    $link = $data['link'] ?? null;
    $featured = isset($data['featured']) ? (int)$data['featured'] : 0;
    $order_index = (int)$data['order_index'];
    $meta_title = $data['meta_title'] ?? null;
    $meta_description = $data['meta_description'] ?? null;
    
    if ($imageSet) {
        $stmt->bind_param('ssssssiissi', $title, $description, $category, $client, $date, $link, $featured, $order_index, $meta_title, $meta_description, $imageValue, $project_id);
    } else {
        $stmt->bind_param('ssssssiiisi', $title, $description, $category, $client, $date, $link, $featured, $order_index, $meta_title, $meta_description, $project_id);
    }
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'تم تحديث المشروع بنجاح'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'حدث خطأ أثناء تحديث المشروع: ' . $stmt->error
        ]);
    }
    
    $stmt->close();
    exit();
}

// Delete a project
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get project ID from URL
    $url_parts = explode('/', $_SERVER['REQUEST_URI']);
    $project_id = end($url_parts);
    
    if (!is_numeric($project_id)) {
        echo json_encode([
            'success' => false,
            'message' => 'معرف المشروع غير صالح'
        ]);
        exit();
    }
    
    // Get the image path first to delete the file
    $query = "SELECT image FROM projects WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
        $image_path = $project['image'];
        
        // Delete the image file if it exists and is not a URL
        if (!empty($image_path) && strpos($image_path, 'http') !== 0) {
            $full_path = '../..' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $image_path);
            if (file_exists($full_path)) {
                @unlink($full_path);
            }
        }
    }
    
    // Prepare and execute SQL query to delete the project
    $query = "DELETE FROM projects WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $project_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'تم حذف المشروع بنجاح'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'حدث خطأ أثناء حذف المشروع: ' . $stmt->error
        ]);
    }
    
    $stmt->close();
    exit();
}

// Close database connection
$conn->close();
?>
