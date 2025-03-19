
<?php
require_once 'config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Invalid request method', 405);
}

// Get form data
$name = trim(htmlspecialchars($_POST['name'] ?? ''));
$email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
$phone = trim(htmlspecialchars($_POST['phone'] ?? ''));
$message = trim(htmlspecialchars($_POST['message'] ?? ''));

// Validate inputs
if (empty($name)) {
    errorResponse('الرجاء إدخال الاسم');
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    errorResponse('الرجاء إدخال بريد إلكتروني صحيح');
}

if (empty($message)) {
    errorResponse('الرجاء إدخال رسالتك');
}

// Store in database
try {
    $stmt = $pdo->prepare("INSERT INTO messages (name, email, phone, message, created_at) VALUES (?, ?, ?, ?, NOW())");
    $result = $stmt->execute([$name, $email, $phone, $message]);
    
    if ($result) {
        // Send email notification (optional)
        // $to = "admin@rocketagency.com";
        // $subject = "New Contact Form Submission";
        // $emailBody = "Name: $name\nEmail: $email\nPhone: $phone\nMessage: $message";
        // mail($to, $subject, $emailBody);
        
        successResponse(null, 'تم إرسال رسالتك بنجاح');
    } else {
        errorResponse('فشل في حفظ الرسالة');
    }
} catch (PDOException $e) {
    errorResponse('خطأ في قاعدة البيانات: ' . $e->getMessage());
}
?>
