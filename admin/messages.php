
<?php
// Start session
session_start();

// Database connection
require_once 'config/db.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page
    header('Location: login.php');
    exit;
}

// Handle message actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Mark as read
    if ($_GET['action'] === 'read') {
        $query = "UPDATE messages SET is_read = 1 WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        header('Location: messages.php');
        exit;
    }
    
    // Delete message
    if ($_GET['action'] === 'delete') {
        $query = "DELETE FROM messages WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        header('Location: messages.php');
        exit;
    }
    
    // View message
    if ($_GET['action'] === 'view') {
        $query = "SELECT * FROM messages WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $message = $result->fetch_assoc();
            
            // Mark as read
            $query = "UPDATE messages SET is_read = 1 WHERE id = ?";
            $update_stmt = $conn->prepare($query);
            $update_stmt->bind_param('i', $id);
            $update_stmt->execute();
        } else {
            header('Location: messages.php');
            exit;
        }
    }
}

// Get all messages
$messages = [];
$query = "SELECT * FROM messages ORDER BY created_at DESC";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الرسائل - لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <span class="logo-text">روكيت</span>
                    <span class="logo-icon">🚀</span>
                </div>
                <p class="sidebar-title">لوحة التحكم</p>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="index.php"><span class="nav-icon">📊</span> الرئيسية</a>
                    </li>
                    <li class="active">
                        <a href="messages.php"><span class="nav-icon">📩</span> الرسائل</a>
                    </li>
                    <li>
                        <a href="projects.php"><span class="nav-icon">🚀</span> المشاريع</a>
                    </li>
                    <li>
                        <a href="services.php"><span class="nav-icon">⚙️</span> الخدمات</a>
                    </li>
                    <li>
                        <a href="team.php"><span class="nav-icon">👥</span> فريق العمل</a>
                    </li>
                    <li>
                        <a href="settings.php"><span class="nav-icon">⚙️</span> الإعدادات</a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn">
                    <span class="icon">🚪</span>
                    <span>تسجيل الخروج</span>
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>إدارة الرسائل</h1>
                    <p>عرض وإدارة رسائل العملاء</p>
                </div>
                
                <div class="header-actions">
                    <a href="../index.html" class="view-site-btn" target="_blank">
                        <span class="icon">🌐</span>
                        <span>عرض الموقع</span>
                    </a>
                </div>
            </header>
            
            <?php if (isset($_GET['action']) && $_GET['action'] === 'view' && isset($message)): ?>
                <!-- View Message -->
                <div class="content-section">
                    <div class="section-header">
                        <h2>عرض الرسالة</h2>
                        <a href="messages.php" class="btn btn-secondary">العودة للقائمة</a>
                    </div>
                    
                    <div class="message-details">
                        <div class="message-header">
                            <div class="message-sender">
                                <h3><?php echo htmlspecialchars($message['name']); ?></h3>
                                <p><?php echo htmlspecialchars($message['email']); ?></p>
                                <?php if (!empty($message['phone'])): ?>
                                    <p><?php echo htmlspecialchars($message['phone']); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="message-date">
                                <p><?php echo date('Y-m-d H:i', strtotime($message['created_at'])); ?></p>
                            </div>
                        </div>
                        
                        <div class="message-content">
                            <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                        </div>
                        
                        <div class="message-actions">
                            <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" class="btn btn-primary">
                                <i class="fas fa-reply"></i> الرد
                            </a>
                            <a href="messages.php?action=delete&id=<?php echo $message['id']; ?>" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه الرسالة؟')">
                                <i class="fas fa-trash"></i> حذف
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Messages List -->
                <div class="content-section">
                    <div class="section-header">
                        <h2>قائمة الرسائل</h2>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>المرسل</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الهاتف</th>
                                    <th>تاريخ الإرسال</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($messages)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">لا توجد رسائل</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($messages as $msg): ?>
                                        <tr class="<?php echo $msg['is_read'] ? '' : 'unread-row'; ?>">
                                            <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                            <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                            <td><?php echo !empty($msg['phone']) ? htmlspecialchars($msg['phone']) : '-'; ?></td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($msg['created_at'])); ?></td>
                                            <td>
                                                <span class="status-badge <?php echo $msg['is_read'] ? 'read' : 'unread'; ?>">
                                                    <?php echo $msg['is_read'] ? 'مقروءة' : 'جديدة'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="messages.php?action=view&id=<?php echo $msg['id']; ?>" class="action-btn view-btn" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if (!$msg['is_read']): ?>
                                                        <a href="messages.php?action=read&id=<?php echo $msg['id']; ?>" class="action-btn read-btn" title="تحديد كمقروءة">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="messages.php?action=delete&id=<?php echo $msg['id']; ?>" class="action-btn delete-btn" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذه الرسالة؟')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script src="assets/js/admin.js"></script>
</body>
</html>
