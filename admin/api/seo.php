
<?php
// Allow CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle OPTIONS request for CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include database configuration
require_once '../config/db.php';

// Get SEO settings
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM seo_settings WHERE id = 1 LIMIT 1";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $settings = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'data' => $settings
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'لم يتم العثور على إعدادات SEO'
        ]);
    }
    exit();
}

// Update SEO settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode([
            'success' => false,
            'message' => 'البيانات المرسلة غير صالحة'
        ]);
        exit();
    }
    
    // Check if SEO settings exist
    $checkQuery = "SELECT COUNT(*) as count FROM seo_settings";
    $result = $conn->query($checkQuery);
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        // Insert new record
        $fields = [];
        $placeholders = [];
        $values = [];
        $types = '';
        
        foreach ($data as $key => $value) {
            if ($key !== 'id' && $key !== 'updated_at') {
                $fields[] = $key;
                $placeholders[] = '?';
                $values[] = $value;
                $types .= 's'; // All fields are strings
            }
        }
        
        $fieldsList = implode(', ', $fields);
        $placeholdersList = implode(', ', $placeholders);
        
        $query = "INSERT INTO seo_settings ({$fieldsList}) VALUES ({$placeholdersList})";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            echo json_encode([
                'success' => false,
                'message' => 'خطأ في إعداد الاستعلام: ' . $conn->error
            ]);
            exit();
        }
        
        $stmt->bind_param($types, ...$values);
    } else {
        // Update existing record
        $updates = [];
        $values = [];
        $types = '';
        
        foreach ($data as $key => $value) {
            if ($key !== 'id' && $key !== 'updated_at') {
                $updates[] = "{$key} = ?";
                $values[] = $value;
                $types .= 's'; // All fields are strings
            }
        }
        
        $updatesList = implode(', ', $updates);
        
        $query = "UPDATE seo_settings SET {$updatesList} WHERE id = 1";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            echo json_encode([
                'success' => false,
                'message' => 'خطأ في إعداد الاستعلام: ' . $conn->error
            ]);
            exit();
        }
        
        $stmt->bind_param($types, ...$values);
    }
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'تم تحديث إعدادات SEO بنجاح'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'حدث خطأ أثناء تحديث إعدادات SEO: ' . $stmt->error
        ]);
    }
    
    $stmt->close();
    exit();
}

// Close database connection
$conn->close();
?>
