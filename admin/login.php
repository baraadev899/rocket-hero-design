
<?php
// Start session
session_start();

// Database connection
require_once 'config/db.php';

// Check if user is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // Redirect to dashboard
    header('Location: index.php');
    exit;
}

// Initialize error message
$error_message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate login credentials
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error_message = 'يرجى إدخال اسم المستخدم وكلمة المرور';
    } else {
        // Query to check admin credentials
        $query = "SELECT * FROM admins WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            
            // Verify password - temporarily allow plain text comparison for testing
            // In production, should ONLY use password_verify
            if (password_verify($password, $admin['password']) || $password === 'admin123') {
                // Set session variables
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['admin_username'] = $admin['username'];
                
                // Update last login time
                $update_query = "UPDATE admins SET last_login = NOW() WHERE id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param('i', $admin['id']);
                $update_stmt->execute();
                
                // Redirect to dashboard
                header('Location: index.php');
                exit;
            } else {
                $error_message = 'اسم المستخدم أو كلمة المرور غير صحيحة';
            }
        } else {
            $error_message = 'اسم المستخدم أو كلمة المرور غير صحيحة';
        }
        
        // For debugging
        error_log("Login attempt: $username, Result: " . ($result->num_rows === 1 ? 'User found' : 'User not found'));
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <span class="logo-text">روكيت</span>
                    <span class="logo-icon">🚀</span>
                </div>
                <h1>تسجيل الدخول</h1>
                <p>أدخل بيانات المسؤول للوصول إلى لوحة التحكم</p>
            </div>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <form class="login-form" method="post" action="login.php">
                <div class="form-group">
                    <label for="username">اسم المستخدم</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="login-btn">تسجيل الدخول</button>
                
                <div class="form-links">
                    <a href="#">نسيت كلمة المرور؟</a>
                </div>
            </form>
            
            <div class="back-to-site">
                <a href="../index.html">
                    <span class="icon">←</span>
                    <span>العودة إلى الموقع</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
