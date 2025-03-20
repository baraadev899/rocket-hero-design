
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

// Handle project action (add/edit/delete)
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$project_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$project = null;

// If editing, get project details
if ($action === 'edit' && $project_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
    } else {
        // Redirect if project not found
        header('Location: projects.php');
        exit;
    }
}

// Get all projects
$projects = [];
$query = "SELECT * FROM projects ORDER BY created_at DESC";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المشاريع - روكيت</title>
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
                    <li>
                        <a href="messages.php"><span class="nav-icon">📩</span> الرسائل</a>
                    </li>
                    <li class="active">
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
                    <h1><?php echo $action === 'add' ? 'إضافة مشروع جديد' : ($action === 'edit' ? 'تعديل مشروع' : 'إدارة المشاريع'); ?></h1>
                    <p><?php echo $action === 'list' ? 'عرض وإدارة جميع المشاريع' : 'قم بتعبئة النموذج أدناه'; ?></p>
                </div>
                
                <div class="header-actions">
                    <?php if ($action === 'list'): ?>
                    <a href="projects.php?action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>إضافة مشروع</span>
                    </a>
                    <?php else: ?>
                    <a href="projects.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        <span>العودة للقائمة</span>
                    </a>
                    <?php endif; ?>
                </div>
            </header>
            
            <!-- Content Section -->
            <div class="content-section">
                <?php if ($action === 'list'): ?>
                <!-- Projects List -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th width="70">الصورة</th>
                                <th width="200">العنوان</th>
                                <th>التصنيف</th>
                                <th width="100">مميز</th>
                                <th width="120">تاريخ الإضافة</th>
                                <th width="120">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($projects) > 0): ?>
                                <?php foreach ($projects as $item): ?>
                                <tr>
                                    <td>
                                        <div class="table-image">
                                            <img src="<?= (strpos($item['image'], 'http') === 0) ? $item['image'] : '../' . $item['image'] ?>" alt="<?= $item['title'] ?>">
                                        </div>
                                    </td>
                                    <td><?= $item['title'] ?></td>
                                    <td><?= $item['category'] ?></td>
                                    <td>
                                        <span class="badge <?= $item['featured'] ? 'badge-success' : 'badge-gray' ?>">
                                            <?= $item['featured'] ? 'مميز' : 'غير مميز' ?>
                                        </span>
                                    </td>
                                    <td dir="ltr"><?= date('Y-m-d', strtotime($item['created_at'])) ?></td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="projects.php?action=edit&id=<?= $item['id'] ?>" class="btn-icon" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn-icon delete-btn" data-id="<?= $item['id'] ?>" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">لا توجد مشاريع مضافة بعد.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php else: ?>
                <!-- Add/Edit Project Form -->
                <div class="form-container">
                    <form id="projectForm" enctype="multipart/form-data">
                        <?php if ($action === 'edit'): ?>
                            <input type="hidden" name="id" value="<?= $project['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="form-row">
                            <label for="title">عنوان المشروع <span class="required">*</span></label>
                            <input type="text" id="title" name="title" required value="<?= $project ? $project['title'] : '' ?>">
                        </div>
                        
                        <div class="form-row">
                            <label for="category">تصنيف المشروع <span class="required">*</span></label>
                            <input type="text" id="category" name="category" required value="<?= $project ? $project['category'] : '' ?>">
                        </div>
                        
                        <div class="form-row">
                            <label for="description">وصف المشروع <span class="required">*</span></label>
                            <textarea id="description" name="description" required rows="5"><?= $project ? $project['description'] : '' ?></textarea>
                        </div>
                        
                        <div class="two-column">
                            <div class="form-row">
                                <label for="client">اسم العميل</label>
                                <input type="text" id="client" name="client" value="<?= $project ? $project['client'] : '' ?>">
                            </div>
                            
                            <div class="form-row">
                                <label for="date">تاريخ المشروع</label>
                                <input type="date" id="date" name="date" value="<?= $project && $project['date'] ? date('Y-m-d', strtotime($project['date'])) : '' ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <label for="link">رابط المشروع</label>
                            <input type="url" id="link" name="link" value="<?= $project ? $project['link'] : '' ?>">
                        </div>
                        
                        <div class="form-row">
                            <label for="image">صورة المشروع <?= $action === 'add' ? '<span class="required">*</span>' : '' ?></label>
                            <input type="file" id="image" name="image" <?= $action === 'add' ? 'required' : '' ?> accept="image/*">
                            <?php if ($action === 'edit' && $project['image']): ?>
                                <div class="current-image">
                                    <img src="<?= (strpos($project['image'], 'http') === 0) ? $project['image'] : '../' . $project['image'] ?>" alt="<?= $project['title'] ?>">
                                    <p>الصورة الحالية. اختر صورة جديدة للتغيير.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-row checkbox-row">
                            <label class="checkbox-container">
                                <input type="checkbox" id="featured" name="featured" <?= $project && $project['featured'] ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                <span class="text">عرض كمشروع مميز</span>
                            </label>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                <span><?= $action === 'add' ? 'إضافة المشروع' : 'حفظ التغييرات' ?></span>
                            </button>
                            <a href="projects.php" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>تأكيد الحذف</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف هذا المشروع؟</p>
                <p class="warning">هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger confirm-delete" data-id="">نعم، حذف</button>
                <button class="btn btn-secondary cancel-delete">إلغاء</button>
            </div>
        </div>
    </div>
    
    <script src="assets/js/admin.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const projectForm = document.getElementById('projectForm');
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteModal = document.getElementById('deleteModal');
            const confirmDeleteBtn = document.querySelector('.confirm-delete');
            const cancelDeleteBtn = document.querySelector('.cancel-delete');
            const closeModalBtn = document.querySelector('.close-modal');
            
            // Handle form submission
            if (projectForm) {
                projectForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(projectForm);
                    const actionType = formData.has('id') ? 'PUT' : 'POST';
                    const projectId = formData.get('id');
                    
                    // Display loading
                    showToast('جاري المعالجة...', 'info');
                    
                    // Convert FormData to proper format for API
                    const projectData = {};
                    formData.forEach((value, key) => {
                        if (key === 'featured') {
                            projectData[key] = 1;
                        } else if (key !== 'image' || (key === 'image' && value.size > 0)) {
                            projectData[key] = value;
                        }
                    });
                    
                    // Handle image upload
                    let uploadPromise;
                    const imageFile = formData.get('image');
                    
                    if (imageFile && imageFile.size > 0) {
                        const uploadFormData = new FormData();
                        uploadFormData.append('image', imageFile);
                        
                        uploadPromise = fetch('api/upload.php', {
                            method: 'POST',
                            body: uploadFormData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                return data.file_path;
                            } else {
                                throw new Error(data.message || 'حدث خطأ أثناء رفع الصورة');
                            }
                        });
                    } else {
                        uploadPromise = Promise.resolve(null);
                    }
                    
                    // Process form submission after handling image upload
                    uploadPromise.then(imagePath => {
                        if (imagePath) {
                            projectData.image = imagePath;
                        }
                        
                        let url = 'api/projects.php';
                        if (actionType === 'PUT') {
                            url += '/' + projectId;
                        }
                        
                        return fetch(url, {
                            method: actionType,
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(projectData)
                        });
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message, 'success');
                            setTimeout(() => {
                                window.location.href = 'projects.php';
                            }, 1000);
                        } else {
                            showToast(data.message || 'حدث خطأ أثناء حفظ المشروع', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('حدث خطأ أثناء المعالجة', 'error');
                    });
                });
            }
            
            // Show delete confirmation modal
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const projectId = this.getAttribute('data-id');
                    confirmDeleteBtn.setAttribute('data-id', projectId);
                    deleteModal.classList.add('show');
                });
            });
            
            // Handle delete confirmation
            confirmDeleteBtn.addEventListener('click', function() {
                const projectId = this.getAttribute('data-id');
                
                fetch(`api/projects.php/${projectId}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showToast(data.message || 'حدث خطأ أثناء حذف المشروع', 'error');
                    }
                    
                    deleteModal.classList.remove('show');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('حدث خطأ أثناء المعالجة', 'error');
                    deleteModal.classList.remove('show');
                });
            });
            
            // Close modal
            cancelDeleteBtn.addEventListener('click', function() {
                deleteModal.classList.remove('show');
            });
            
            closeModalBtn.addEventListener('click', function() {
                deleteModal.classList.remove('show');
            });
            
            // Close modal on outside click
            window.addEventListener('click', function(event) {
                if (event.target === deleteModal) {
                    deleteModal.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>
