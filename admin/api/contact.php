
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

// Submit contact form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($data['name']) || empty($data['email']) || empty($data['subject']) || empty($data['message'])) {
        echo json_encode([
            'success' => false,
            'message' => 'يرجى ملء جميع الحقول المطلوبة'
        ]);
        exit();
    }
    
    // Prepare and execute SQL query
    $query = "INSERT INTO messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    $name = $data['name'];
    $email = $data['email'];
    $phone = isset($data['phone']) ? $data['phone'] : null;
    $subject = $data['subject'];
    $message = $data['message'];
    
    $stmt->bind_param('sssss', $name, $email, $phone, $subject, $message);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'تم إرسال رسالتك بنجاح!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'حدث خطأ أثناء إرسال الرسالة: ' . $stmt->error
        ]);
    }
    
    $stmt->close();
    exit();
}

// Close database connection
$conn->close();
?>
