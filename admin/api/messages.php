
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

// Get all messages
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM messages ORDER BY created_at DESC";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'data' => $messages
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'data' => []
        ]);
    }
    exit();
}

// Add new message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['message'])) {
        echo json_encode([
            'success' => false,
            'message' => 'يرجى ملء جميع الحقول المطلوبة'
        ]);
        exit();
    }
    
    // Sanitize input
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : '';
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'يرجى إدخال بريد إلكتروني صحيح'
        ]);
        exit();
    }
    
    // Insert message into database
    $query = "INSERT INTO messages (name, email, phone, subject, message, ip_address) VALUES ('$name', '$email', '$phone', '$subject', '$message', '$ip')";
    
    if ($conn->query($query) === TRUE) {
        echo json_encode([
            'success' => true,
            'message' => 'تم إرسال الرسالة بنجاح'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'حدث خطأ أثناء إرسال الرسالة: ' . $conn->error
        ]);
    }
    
    exit();
}

// Close database connection
$conn->close();
?>
