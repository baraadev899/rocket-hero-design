
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

// Get dashboard statistics
$stats = [];

// Total messages
$query = "SELECT COUNT(*) as total FROM messages";
$result = $conn->query($query);
$stats['total_messages'] = $result->fetch_assoc()['total'];

// New messages (unread)
$query = "SELECT COUNT(*) as total FROM messages WHERE status = 'unread'";
$result = $conn->query($query);
$stats['new_messages'] = $result->fetch_assoc()['total'];

// Total projects
$query = "SELECT COUNT(*) as total FROM projects";
$result = $conn->query($query);
$stats['total_projects'] = $result->fetch_assoc()['total'];

// Total team members
$query = "SELECT COUNT(*) as total FROM team";
$result = $conn->query($query);
$stats['total_team'] = $result->fetch_assoc()['total'];

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - روكيت</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
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
                    <li class="active">
                        <a href="index.php"><span class="nav-icon">📊</span> الرئيسية</a>
                    </li>
                    <li>
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
                    <h1>لوحة التحكم</h1>
                    <p>مرحباً، <?php echo htmlspecialchars($_SESSION['admin_name']); ?>! هذه نظرة عامة على موقعك.</p>
                </div>
                
                <div class="header-actions">
                    <a href="../index.html" class="view-site-btn" target="_blank">
                        <span class="icon">🌐</span>
                        <span>عرض الموقع</span>
                    </a>
                </div>
            </header>
            
            <!-- Stats Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-icon messages-icon">📩</div>
                    <div class="stat-details">
                        <div class="stat-number"><?php echo $stats['total_messages']; ?></div>
                        <div class="stat-label">إجمالي الرسائل</div>
                    </div>
                    <div class="stat-badge">
                        <span><?php echo $stats['new_messages']; ?> جديدة</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon projects-icon">🚀</div>
                    <div class="stat-details">
                        <div class="stat-number"><?php echo $stats['total_projects']; ?></div>
                        <div class="stat-label">إجمالي المشاريع</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon team-icon">👥</div>
                    <div class="stat-details">
                        <div class="stat-number"><?php echo $stats['total_team']; ?></div>
                        <div class="stat-label">أعضاء الفريق</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon visitors-icon">👥</div>
                    <div class="stat-details">
                        <div class="stat-number">1,256</div>
                        <div class="stat-label">زيارات اليوم</div>
                    </div>
                    <div class="stat-badge positive">
                        <span>↑ 12%</span>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activities -->
            <div class="content-section">
                <div class="section-header">
                    <h2>آخر النشاطات</h2>
                </div>
                
                <div class="activities-list">
                    <?php
                    // Get recent messages
                    $query = "SELECT * FROM messages ORDER BY created_at DESC LIMIT 5";
                    $result = $conn->query($query);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $date = date('Y-m-d H:i', strtotime($row['created_at']));
                            $status_class = $row['status'] === 'unread' ? 'unread' : 'read';
                            echo <<<HTML
                            <div class="activity-item">
                                <div class="activity-icon message-icon">📩</div>
                                <div class="activity-details">
                                    <div class="activity-title">رسالة جديدة من {$row['name']}</div>
                                    <div class="activity-subtitle">{$row['email']} - {$row['subject']}</div>
                                </div>
                                <div class="activity-meta">
                                    <span class="activity-time">{$date}</span>
                                    <span class="activity-status {$status_class}">{$row['status']}</span>
                                </div>
                            </div>
                            HTML;
                        }
                    } else {
                        echo '<p>لا توجد رسائل حديثة.</p>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Quick Actions and Recent Projects in same row -->
            <div class="two-column-layout">
                <!-- Quick Actions -->
                <div class="content-section">
                    <div class="section-header">
                        <h2>إجراءات سريعة</h2>
                    </div>
                    
                    <div class="quick-actions">
                        <a href="messages.php" class="quick-action-btn">
                            <span class="action-icon">📩</span>
                            <span class="action-text">إدارة الرسائل</span>
                        </a>
                        
                        <a href="projects.php?action=add" class="quick-action-btn">
                            <span class="action-icon">➕</span>
                            <span class="action-text">إضافة مشروع</span>
                        </a>
                        
                        <a href="team.php?action=add" class="quick-action-btn">
                            <span class="action-icon">👥</span>
                            <span class="action-text">إضافة عضو فريق</span>
                        </a>
                        
                        <a href="services.php?action=add" class="quick-action-btn">
                            <span class="action-icon">⚙️</span>
                            <span class="action-text">إضافة خدمة</span>
                        </a>
                    </div>
                </div>
                
                <!-- Recent Projects -->
                <div class="content-section">
                    <div class="section-header">
                        <h2>أحدث المشاريع</h2>
                    </div>
                    
                    <div class="recent-projects">
                        <?php
                        // Get recent projects
                        $query = "SELECT * FROM projects ORDER BY created_at DESC LIMIT 3";
                        $result = $conn->query($query);
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo <<<HTML
                                <div class="project-item">
                                    <div class="project-image">
                                        <img src="../{$row['image']}" alt="{$row['title']}">
                                    </div>
                                    <div class="project-details">
                                        <div class="project-title">{$row['title']}</div>
                                        <div class="project-category">{$row['category']}</div>
                                    </div>
                                </div>
                                HTML;
                            }
                        } else {
                            echo '<p>لا توجد مشاريع حديثة.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="assets/js/admin.js"></script>
</body>
</html>
