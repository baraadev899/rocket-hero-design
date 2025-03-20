
<?php
// Allow CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle OPTIONS request for CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'يجب استخدام طريقة POST'
    ]);
    exit();
}

// Check if files were uploaded
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $error = isset($_FILES['image']) ? $_FILES['image']['error'] : 'No file uploaded';
    
    // Translate error codes to messages
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE => 'حجم الملف يتجاوز الحد المسموح به في إعدادات PHP (upload_max_filesize)',
        UPLOAD_ERR_FORM_SIZE => 'حجم الملف يتجاوز الحد المسموح به في النموذج',
        UPLOAD_ERR_PARTIAL => 'تم تحميل جزء من الملف فقط',
        UPLOAD_ERR_NO_FILE => 'لم يتم تحميل أي ملف',
        UPLOAD_ERR_NO_TMP_DIR => 'المجلد المؤقت مفقود',
        UPLOAD_ERR_CANT_WRITE => 'فشل في كتابة الملف على القرص',
        UPLOAD_ERR_EXTENSION => 'توقف التحميل بسبب امتداد PHP'
    ];
    
    $errorMessage = isset($errorMessages[$error]) ? $errorMessages[$error] : 'خطأ غير معروف: ' . $error;
    
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في تحميل الملف: ' . $errorMessage
    ]);
    exit();
}

// Get file information
$file = $_FILES['image'];
$filename = $file['name'];
$tmp_name = $file['tmp_name'];
$file_size = $file['size'];
$file_type = $file['type'];

// Validate file type
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/x-icon'];
if (!in_array($file_type, $allowed_types)) {
    echo json_encode([
        'success' => false,
        'message' => 'نوع الملف غير مسموح به. الأنواع المسموح بها هي: JPG, PNG, GIF, WEBP, SVG, ICO'
    ]);
    exit();
}

// Validate file size (max 5MB)
$max_size = 5 * 1024 * 1024; // 5MB
if ($file_size > $max_size) {
    echo json_encode([
        'success' => false,
        'message' => 'حجم الملف يتجاوز الحد المسموح به (5 ميغابايت)'
    ]);
    exit();
}

// Generate unique filename to prevent overwriting
$ext = pathinfo($filename, PATHINFO_EXTENSION);
$new_filename = uniqid() . '_' . time() . '.' . $ext;

// Set upload directory relative to admin folder
$upload_dir = '../assets/uploads/';

// Create directory if it doesn't exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Set file path
$file_path = $upload_dir . $new_filename;

// Move uploaded file
if (move_uploaded_file($tmp_name, $file_path)) {
    // Return file path relative to site root
    $relative_path = 'assets/uploads/' . $new_filename;
    
    echo json_encode([
        'success' => true,
        'message' => 'تم رفع الملف بنجاح',
        'file_path' => $relative_path,
        'file_name' => $new_filename,
        'file_size' => $file_size,
        'file_type' => $file_type
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'فشل في نقل الملف المرفوع. تأكد من صلاحيات المجلدات.'
    ]);
}
?>
