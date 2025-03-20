
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

// Handle team member action (add/edit/delete)
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$member_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$member = null;

// If editing, get team member details
if ($action === 'edit' && $member_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM team WHERE id = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $member = $result->fetch_assoc();
    } else {
        // Redirect if member not found
        header('Location: team.php');
        exit;
    }
}

// Get all team members
$team = [];
$query = "SELECT * FROM team ORDER BY order_index, id ASC";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $team[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة فريق العمل - روكيت</title>
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
                    <li>
                        <a href="projects.php"><span class="nav-icon">🚀</span> المشاريع</a>
                    </li>
                    <li>
                        <a href="services.php"><span class="nav-icon">⚙️</span> الخدمات</a>
                    </li>
                    <li class="active">
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
                    <h1><?php echo $action === 'add' ? 'إضافة عضو جديد' : ($action === 'edit' ? 'تعديل عضو' : 'إدارة فريق العمل'); ?></h1>
                    <p><?php echo $action === 'list' ? 'عرض وإدارة أعضاء الفريق' : 'قم بتعبئة النموذج أدناه'; ?></p>
                </div>
                
                <div class="header-actions">
                    <?php if ($action === 'list'): ?>
                    <a href="team.php?action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>إضافة عضو</span>
                    </a>
                    <?php else: ?>
                    <a href="team.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        <span>العودة للقائمة</span>
                    </a>
                    <?php endif; ?>
                </div>
            </header>
            
            <!-- Content Section -->
            <div class="content-section">
                <?php if ($action === 'list'): ?>
                <!-- Team Members List -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th width="70">الصورة</th>
                                <th width="180">الاسم</th>
                                <th width="150">المنصب</th>
                                <th>البريد الإلكتروني</th>
                                <th width="80">الترتيب</th>
                                <th width="120">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($team) > 0): ?>
                                <?php foreach ($team as $item): ?>
                                <tr>
                                    <td>
                                        <div class="table-image">
                                            <img src="<?= (strpos($item['image'], 'http') === 0) ? $item['image'] : '../' . $item['image'] ?>" alt="<?= $item['name'] ?>">
                                        </div>
                                    </td>
                                    <td><?= $item['name'] ?></td>
                                    <td><?= $item['position'] ?></td>
                                    <td><?= $item['email'] ?? '-' ?></td>
                                    <td><?= $item['order_index'] ?></td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="team.php?action=edit&id=<?= $item['id'] ?>" class="btn-icon" title="تعديل">
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
                                    <td colspan="6" class="text-center">لا يوجد أعضاء في الفريق بعد.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php else: ?>
                <!-- Add/Edit Team Member Form -->
                <div class="form-container">
                    <form id="teamForm" enctype="multipart/form-data">
                        <?php if ($action === 'edit'): ?>
                            <input type="hidden" name="id" value="<?= $member['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="form-row">
                            <label for="name">الاسم <span class="required">*</span></label>
                            <input type="text" id="name" name="name" required value="<?= $member ? $member['name'] : '' ?>">
                        </div>
                        
                        <div class="form-row">
                            <label for="position">المنصب <span class="required">*</span></label>
                            <input type="text" id="position" name="position" required value="<?= $member ? $member['position'] : '' ?>">
                        </div>
                        
                        <div class="form-row">
                            <label for="bio">نبذة عن العضو</label>
                            <textarea id="bio" name="bio" rows="4"><?= $member ? $member['bio'] : '' ?></textarea>
                        </div>
                        
                        <div class="two-column">
                            <div class="form-row">
                                <label for="email">البريد الإلكتروني</label>
                                <input type="email" id="email" name="email" value="<?= $member ? $member['email'] : '' ?>">
                            </div>
                            
                            <div class="form-row">
                                <label for="order_index">ترتيب العرض</label>
                                <input type="number" id="order_index" name="order_index" min="0" value="<?= $member ? $member['order_index'] : '0' ?>">
                                <p class="form-help">الأرقام الأصغر تظهر أولاً</p>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <label for="image">الصورة الشخصية <?= $action === 'add' ? '<span class="required">*</span>' : '' ?></label>
                            <input type="file" id="image" name="image" <?= $action === 'add' ? 'required' : '' ?> accept="image/*">
                            <?php if ($action === 'edit' && $member['image']): ?>
                                <div class="current-image">
                                    <img src="<?= (strpos($member['image'], 'http') === 0) ? $member['image'] : '../' . $member['image'] ?>" alt="<?= $member['name'] ?>">
                                    <p>الصورة الحالية. اختر صورة جديدة للتغيير.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="section-divider">
                            <span>روابط التواصل الاجتماعي</span>
                        </div>
                        
                        <div class="social-links">
                            <div class="form-row">
                                <label for="twitter">
                                    <i class="fab fa-twitter"></i> تويتر
                                </label>
                                <input type="url" id="twitter" name="twitter" value="<?= $member ? $member['twitter'] : '' ?>" placeholder="https://twitter.com/username">
                            </div>
                            
                            <div class="form-row">
                                <label for="linkedin">
                                    <i class="fab fa-linkedin"></i> لينكد إن
                                </label>
                                <input type="url" id="linkedin" name="linkedin" value="<?= $member ? $member['linkedin'] : '' ?>" placeholder="https://linkedin.com/in/username">
                            </div>
                            
                            <div class="form-row">
                                <label for="instagram">
                                    <i class="fab fa-instagram"></i> انستغرام
                                </label>
                                <input type="url" id="instagram" name="instagram" value="<?= $member ? $member['instagram'] : '' ?>" placeholder="https://instagram.com/username">
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                <span><?= $action === 'add' ? 'إضافة العضو' : 'حفظ التغييرات' ?></span>
                            </button>
                            <a href="team.php" class="btn btn-secondary">إلغاء</a>
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
                <p>هل أنت متأكد من حذف هذا العضو من الفريق؟</p>
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
            const teamForm = document.getElementById('teamForm');
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteModal = document.getElementById('deleteModal');
            const confirmDeleteBtn = document.querySelector('.confirm-delete');
            const cancelDeleteBtn = document.querySelector('.cancel-delete');
            const closeModalBtn = document.querySelector('.close-modal');
            
            // Handle form submission
            if (teamForm) {
                teamForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(teamForm);
                    const actionType = formData.has('id') ? 'PUT' : 'POST';
                    const memberId = formData.get('id');
                    
                    // Display loading
                    showToast('جاري المعالجة...', 'info');
                    
                    // Convert FormData to proper format for API
                    const memberData = {};
                    formData.forEach((value, key) => {
                        if (key !== 'image' || (key === 'image' && value.size > 0)) {
                            memberData[key] = value;
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
                            memberData.image = imagePath;
                        }
                        
                        let url = 'api/team.php';
                        if (actionType === 'PUT') {
                            url += '/' + memberId;
                        }
                        
                        return fetch(url, {
                            method: actionType,
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(memberData)
                        });
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message, 'success');
                            setTimeout(() => {
                                window.location.href = 'team.php';
                            }, 1000);
                        } else {
                            showToast(data.message || 'حدث خطأ أثناء حفظ بيانات العضو', 'error');
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
                    const memberId = this.getAttribute('data-id');
                    confirmDeleteBtn.setAttribute('data-id', memberId);
                    deleteModal.classList.add('show');
                });
            });
            
            // Handle delete confirmation
            confirmDeleteBtn.addEventListener('click', function() {
                const memberId = this.getAttribute('data-id');
                
                fetch(`api/team.php/${memberId}`, {
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
                        showToast(data.message || 'حدث خطأ أثناء حذف العضو', 'error');
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
