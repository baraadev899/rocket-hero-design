
<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root';  // قم بتغيير اسم مستخدم قاعدة البيانات الخاص بك
$db_pass = '';      // قم بتغيير كلمة مرور قاعدة البيانات الخاصة بك
$db_name = 'rocket_agency';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// Set character set to utf8mb4
$conn->set_charset("utf8mb4");
?>
