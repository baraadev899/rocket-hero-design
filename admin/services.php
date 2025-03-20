
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

// Handle service action (add/edit/delete)
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$service_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$service = null;

// If editing, get service details
if ($action === 'edit' && $service_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $service = $result->fetch_assoc();
    } else {
        // Redirect if service not found
        header('Location: services.php');
        exit;
    }
}

// Get all services
$services = [];
$query = "SELECT * FROM services ORDER BY order_index, id ASC";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الخدمات - روكيت</title>
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
                    <li class="active">
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
                    <h1><?php echo $action === 'add' ? 'إضافة خدمة جديدة' : ($action === 'edit' ? 'تعديل خدمة' : 'إدارة الخدمات'); ?></h1>
                    <p><?php echo $action === 'list' ? 'عرض وإدارة جميع الخدمات' : 'قم بتعبئة النموذج أدناه'; ?></p>
                </div>
                
                <div class="header-actions">
                    <?php if ($action === 'list'): ?>
                    <a href="services.php?action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>إضافة خدمة</span>
                    </a>
                    <?php else: ?>
                    <a href="services.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        <span>العودة للقائمة</span>
                    </a>
                    <?php endif; ?>
                </div>
            </header>
            
            <!-- Content Section -->
            <div class="content-section">
                <?php if ($action === 'list'): ?>
                <!-- Services List -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th width="70">الأيقونة</th>
                                <th width="200">العنوان</th>
                                <th>الوصف</th>
                                <th width="80">الترتيب</th>
                                <th width="120">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="servicesList">
                            <?php if (count($services) > 0): ?>
                                <?php foreach ($services as $item): ?>
                                <tr>
                                    <td>
                                        <div class="service-icon">
                                            <i class="fas <?= $item['icon'] ?>"></i>
                                        </div>
                                    </td>
                                    <td><?= $item['title'] ?></td>
                                    <td>
                                        <?= strlen($item['description']) > 100 ? substr($item['description'], 0, 100) . '...' : $item['description'] ?>
                                    </td>
                                    <td><?= $item['order_index'] ?></td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="services.php?action=edit&id=<?= $item['id'] ?>" class="btn-icon" title="تعديل">
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
                                    <td colspan="5" class="text-center">لا توجد خدمات مضافة بعد.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php else: ?>
                <!-- Add/Edit Service Form -->
                <div class="form-container">
                    <form id="serviceForm">
                        <?php if ($action === 'edit'): ?>
                            <input type="hidden" name="id" value="<?= $service['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="form-row">
                            <label for="title">عنوان الخدمة <span class="required">*</span></label>
                            <input type="text" id="title" name="title" required value="<?= $service ? $service['title'] : '' ?>">
                        </div>
                        
                        <div class="form-row">
                            <label for="description">وصف الخدمة <span class="required">*</span></label>
                            <textarea id="description" name="description" required rows="5"><?= $service ? $service['description'] : '' ?></textarea>
                        </div>
                        
                        <div class="form-row">
                            <label for="features">مميزات الخدمة (افصل بينها بفاصلة)</label>
                            <textarea id="features" name="features" rows="3"><?= $service ? $service['features'] : '' ?></textarea>
                            <p class="form-help">أدخل ميزات الخدمة مفصولة بفواصل، مثال: ميزة 1، ميزة 2، ميزة 3</p>
                        </div>
                        
                        <div class="two-column">
                            <div class="form-row">
                                <label for="icon">أيقونة الخدمة</label>
                                <div class="icon-selector">
                                    <input type="text" id="icon" name="icon" value="<?= $service ? str_replace('fa-', '', $service['icon']) : 'rocket' ?>" placeholder="rocket">
                                    <div class="icon-preview">
                                        <i class="fas fa-<?= $service ? str_replace('fa-', '', $service['icon']) : 'rocket' ?>"></i>
                                    </div>
                                </div>
                                <p class="form-help">اختر أيقونة من Font Awesome، مثال: rocket, star, cog ...</p>
                            </div>
                            
                            <div class="form-row">
                                <label for="order_index">ترتيب العرض</label>
                                <input type="number" id="order_index" name="order_index" min="0" value="<?= $service ? $service['order_index'] : '0' ?>">
                                <p class="form-help">الأرقام الأصغر تظهر أولاً</p>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <label for="image">صورة الخدمة</label>
                            <input type="file" id="image" name="image" accept="image/*">
                            <?php if ($action === 'edit' && !empty($service['image'])): ?>
                                <div class="current-image">
                                    <img src="<?= (strpos($service['image'], 'http') === 0) ? $service['image'] : '../' . $service['image'] ?>" alt="<?= $service['title'] ?>">
                                    <p>الصورة الحالية. اختر صورة جديدة للتغيير.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                <span><?= $action === 'add' ? 'إضافة الخدمة' : 'حفظ التغييرات' ?></span>
                            </button>
                            <a href="services.php" class="btn btn-secondary">إلغاء</a>
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
                <p>هل أنت متأكد من حذف هذه الخدمة؟</p>
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
            const serviceForm = document.getElementById('serviceForm');
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteModal = document.getElementById('deleteModal');
            const confirmDeleteBtn = document.querySelector('.confirm-delete');
            const cancelDeleteBtn = document.querySelector('.cancel-delete');
            const closeModalBtn = document.querySelector('.close-modal');
            const iconInput = document.getElementById('icon');
            const iconPreview = document.querySelector('.icon-preview i');
            
            // Update icon preview on input change
            if (iconInput && iconPreview) {
                iconInput.addEventListener('input', function() {
                    const iconName = this.value.trim() || 'rocket';
                    iconPreview.className = `fas fa-${iconName}`;
                });
            }
            
            // Handle form submission
            if (serviceForm) {
                serviceForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(serviceForm);
                    const actionType = formData.has('id') ? 'PUT' : 'POST';
                    const serviceId = formData.get('id');
                    
                    // Display loading
                    showToast('جاري المعالجة...', 'info');
                    
                    // Fix icon format (add fa- prefix if needed)
                    let iconValue = formData.get('icon');
                    if (iconValue) {
                        iconValue = iconValue.trim();
                        if (iconValue && !iconValue.startsWith('fa-')) {
                            iconValue = `fa-${iconValue}`;
                        }
                    } else {
                        iconValue = 'fa-rocket';
                    }
                    
                    // Convert FormData to proper format for API
                    const serviceData = {};
                    formData.forEach((value, key) => {
                        if (key === 'icon') {
                            serviceData[key] = iconValue;
                        } else if (key !== 'image' || (key === 'image' && value.size > 0)) {
                            serviceData[key] = value;
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
                            serviceData.image = imagePath;
                        }
                        
                        let url = 'api/services.php';
                        if (actionType === 'PUT') {
                            url += '/' + serviceId;
                        }
                        
                        return fetch(url, {
                            method: actionType,
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(serviceData)
                        });
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message, 'success');
                            setTimeout(() => {
                                window.location.href = 'services.php';
                            }, 1000);
                        } else {
                            showToast(data.message || 'حدث خطأ أثناء حفظ الخدمة', 'error');
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
                    const serviceId = this.getAttribute('data-id');
                    confirmDeleteBtn.setAttribute('data-id', serviceId);
                    deleteModal.classList.add('show');
                });
            });
            
            // Handle delete confirmation
            confirmDeleteBtn.addEventListener('click', function() {
                const serviceId = this.getAttribute('data-id');
                
                fetch(`api/services.php/${serviceId}`, {
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
                        showToast(data.message || 'حدث خطأ أثناء حذف الخدمة', 'error');
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
