
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

// Get settings
$settings = [];
$query = "SELECT * FROM settings WHERE id = 1 LIMIT 1";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    $settings = $result->fetch_assoc();
}

// Get SEO settings
$seo_settings = [];
$query = "SELECT * FROM seo_settings WHERE id = 1 LIMIT 1";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    $seo_settings = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الإعدادات - روكيت</title>
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
                    <li>
                        <a href="team.php"><span class="nav-icon">👥</span> فريق العمل</a>
                    </li>
                    <li class="active">
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
                    <h1>إعدادات الموقع</h1>
                    <p>قم بتعديل إعدادات الموقع الأساسية وبيانات التواصل</p>
                </div>
                
                <div class="header-actions">
                    <a href="../index.html" class="view-site-btn" target="_blank">
                        <span class="icon">🌐</span>
                        <span>عرض الموقع</span>
                    </a>
                </div>
            </header>
            
            <!-- Tabs Container -->
            <div class="tabs-container">
                <div class="tabs-nav">
                    <button class="tab-btn active" data-tab="general">إعدادات عامة</button>
                    <button class="tab-btn" data-tab="contact">بيانات التواصل</button>
                    <button class="tab-btn" data-tab="social">وسائل التواصل</button>
                    <button class="tab-btn" data-tab="seo">تحسين محركات البحث (SEO)</button>
                </div>
                
                <div class="tabs-content">
                    <!-- General Settings Tab -->
                    <div class="tab-pane active" id="general">
                        <div class="content-section">
                            <form id="generalSettingsForm">
                                <div class="form-row">
                                    <label for="site_title">عنوان الموقع <span class="required">*</span></label>
                                    <input type="text" id="site_title" name="site_title" required value="<?= $settings['site_title'] ?? 'روكيت للتصميم والبرمجة' ?>">
                                </div>
                                
                                <div class="form-row">
                                    <label for="site_description">وصف الموقع</label>
                                    <textarea id="site_description" name="site_description" rows="3"><?= $settings['site_description'] ?? '' ?></textarea>
                                </div>
                                
                                <div class="form-row">
                                    <label for="logo">شعار الموقع</label>
                                    <input type="file" id="logo" name="logo" accept="image/*">
                                    <?php if (!empty($settings['logo'])): ?>
                                        <div class="current-image">
                                            <img src="<?= '../' . $settings['logo'] ?>" alt="شعار الموقع">
                                            <p>الشعار الحالي. اختر صورة جديدة للتغيير.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="form-row">
                                    <label for="favicon">أيقونة الموقع (Favicon)</label>
                                    <input type="file" id="favicon" name="favicon" accept="image/x-icon,image/png">
                                    <?php if (!empty($settings['favicon'])): ?>
                                        <div class="current-image">
                                            <img src="<?= '../' . $settings['favicon'] ?>" alt="أيقونة الموقع">
                                            <p>الأيقونة الحالية. اختر صورة جديدة للتغيير.</p>
                                        </div>
                                    <?php endif; ?>
                                    <p class="form-help">يفضل استخدام صورة مربعة بحجم 32×32 بكسل أو 64×64 بكسل</p>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i>
                                        <span>حفظ الإعدادات</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Contact Tab -->
                    <div class="tab-pane" id="contact">
                        <div class="content-section">
                            <form id="contactSettingsForm">
                                <div class="form-row">
                                    <label for="email">البريد الإلكتروني</label>
                                    <input type="email" id="email" name="email" value="<?= $settings['email'] ?? '' ?>">
                                </div>
                                
                                <div class="form-row">
                                    <label for="phone">رقم الهاتف</label>
                                    <input type="text" id="phone" name="phone" value="<?= $settings['phone'] ?? '' ?>">
                                </div>
                                
                                <div class="form-row">
                                    <label for="address">العنوان</label>
                                    <textarea id="address" name="address" rows="2"><?= $settings['address'] ?? '' ?></textarea>
                                </div>
                                
                                <div class="form-row">
                                    <label for="google_maps">رابط خريطة جوجل</label>
                                    <input type="url" id="google_maps" name="google_maps" value="<?= $settings['google_maps'] ?? '' ?>">
                                </div>
                                
                                <div class="form-row">
                                    <label for="contact_form_email">البريد الإلكتروني لاستلام الرسائل</label>
                                    <input type="email" id="contact_form_email" name="contact_form_email" value="<?= $settings['contact_form_email'] ?? '' ?>">
                                    <p class="form-help">سيتم إرسال رسائل نموذج الاتصال إلى هذا البريد الإلكتروني</p>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i>
                                        <span>حفظ الإعدادات</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Social Media Tab -->
                    <div class="tab-pane" id="social">
                        <div class="content-section">
                            <form id="socialSettingsForm">
                                <div class="form-row">
                                    <label for="facebook">
                                        <i class="fab fa-facebook"></i> فيسبوك
                                    </label>
                                    <input type="url" id="facebook" name="facebook" value="<?= $settings['facebook'] ?? '' ?>" placeholder="https://facebook.com/yourpage">
                                </div>
                                
                                <div class="form-row">
                                    <label for="twitter">
                                        <i class="fab fa-twitter"></i> تويتر
                                    </label>
                                    <input type="url" id="twitter" name="twitter" value="<?= $settings['twitter'] ?? '' ?>" placeholder="https://twitter.com/username">
                                </div>
                                
                                <div class="form-row">
                                    <label for="instagram">
                                        <i class="fab fa-instagram"></i> انستغرام
                                    </label>
                                    <input type="url" id="instagram" name="instagram" value="<?= $settings['instagram'] ?? '' ?>" placeholder="https://instagram.com/username">
                                </div>
                                
                                <div class="form-row">
                                    <label for="linkedin">
                                        <i class="fab fa-linkedin"></i> لينكد إن
                                    </label>
                                    <input type="url" id="linkedin" name="linkedin" value="<?= $settings['linkedin'] ?? '' ?>" placeholder="https://linkedin.com/company/name">
                                </div>
                                
                                <div class="form-row">
                                    <label for="whatsapp">
                                        <i class="fab fa-whatsapp"></i> واتساب
                                    </label>
                                    <input type="text" id="whatsapp" name="whatsapp" value="<?= $settings['whatsapp'] ?? '' ?>" placeholder="+966501234567">
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i>
                                        <span>حفظ الإعدادات</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- SEO Settings Tab -->
                    <div class="tab-pane" id="seo">
                        <div class="content-section">
                            <form id="seoSettingsForm">
                                <div class="form-row">
                                    <label for="meta_title">عنوان الصفحة الرئيسية (Title)</label>
                                    <input type="text" id="meta_title" name="meta_title" value="<?= $seo_settings['meta_title'] ?? '' ?>">
                                    <p class="form-help">يظهر في شريط عنوان المتصفح وفي نتائج البحث (يفضل أقل من 60 حرف)</p>
                                </div>
                                
                                <div class="form-row">
                                    <label for="meta_description">وصف الصفحة الرئيسية (Meta Description)</label>
                                    <textarea id="meta_description" name="meta_description" rows="3"><?= $seo_settings['meta_description'] ?? '' ?></textarea>
                                    <p class="form-help">يظهر في نتائج البحث كوصف للموقع (يفضل 150-160 حرف)</p>
                                </div>
                                
                                <div class="form-row">
                                    <label for="meta_keywords">الكلمات المفتاحية (Meta Keywords)</label>
                                    <input type="text" id="meta_keywords" name="meta_keywords" value="<?= $seo_settings['meta_keywords'] ?? '' ?>">
                                    <p class="form-help">افصل بين الكلمات بفواصل، مثال: تصميم مواقع، برمجة، جرافيك</p>
                                </div>
                                
                                <div class="form-row">
                                    <label for="og_image">صورة المشاركة الاجتماعية (OG Image)</label>
                                    <input type="file" id="og_image" name="og_image" accept="image/*">
                                    <?php if (!empty($seo_settings['og_image'])): ?>
                                        <div class="current-image">
                                            <img src="<?= '../' . $seo_settings['og_image'] ?>" alt="صورة المشاركة">
                                            <p>الصورة الحالية. اختر صورة جديدة للتغيير.</p>
                                        </div>
                                    <?php endif; ?>
                                    <p class="form-help">تظهر هذه الصورة عند مشاركة رابط موقعك على وسائل التواصل الاجتماعي (يفضل 1200×630 بكسل)</p>
                                </div>
                                
                                <div class="form-row">
                                    <label for="google_analytics">كود Google Analytics</label>
                                    <textarea id="google_analytics" name="google_analytics" rows="4"><?= $seo_settings['google_analytics'] ?? '' ?></textarea>
                                    <p class="form-help">أدخل كود تتبع Google Analytics كاملاً</p>
                                </div>
                                
                                <div class="form-row">
                                    <label for="robots_txt">محتوى ملف robots.txt</label>
                                    <textarea id="robots_txt" name="robots_txt" rows="5"><?= $seo_settings['robots_txt'] ?? "User-agent: *\nAllow: /\nSitemap: https://yourdomain.com/sitemap.xml" ?></textarea>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i>
                                        <span>حفظ إعدادات SEO</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="assets/js/admin.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab navigation
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons and panes
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));
                    
                    // Add active class to clicked button and corresponding pane
                    this.classList.add('active');
                    const targetTab = this.getAttribute('data-tab');
                    document.getElementById(targetTab).classList.add('active');
                });
            });
            
            // Handle form submissions
            const forms = [
                {
                    id: 'generalSettingsForm',
                    endpoint: 'api/settings.php',
                    type: 'general'
                },
                {
                    id: 'contactSettingsForm',
                    endpoint: 'api/settings.php',
                    type: 'contact'
                },
                {
                    id: 'socialSettingsForm',
                    endpoint: 'api/settings.php',
                    type: 'social'
                },
                {
                    id: 'seoSettingsForm',
                    endpoint: 'api/seo.php',
                    type: 'seo'
                }
            ];
            
            forms.forEach(formConfig => {
                const form = document.getElementById(formConfig.id);
                
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        // Display loading
                        showToast('جاري حفظ الإعدادات...', 'info');
                        
                        const formData = new FormData(form);
                        formData.append('type', formConfig.type);
                        
                        // Handle file uploads separately
                        const fileInputs = form.querySelectorAll('input[type="file"]');
                        let fileUploads = [];
                        
                        fileInputs.forEach(input => {
                            if (input.files.length > 0) {
                                const file = input.files[0];
                                const fieldName = input.name;
                                
                                const uploadData = new FormData();
                                uploadData.append('image', file);
                                
                                fileUploads.push({
                                    fieldName: fieldName,
                                    promise: fetch('api/upload.php', {
                                        method: 'POST',
                                        body: uploadData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            return { field: fieldName, path: data.file_path };
                                        } else {
                                            throw new Error(`Error uploading ${fieldName}: ${data.message}`);
                                        }
                                    })
                                });
                            }
                        });
                        
                        // Convert form data to object
                        const settingsData = {};
                        formData.forEach((value, key) => {
                            if (key !== 'type' && !fileInputs.forEach(input => input.name === key)) {
                                settingsData[key] = value;
                            }
                        });
                        
                        // Process all file uploads first
                        Promise.all(fileUploads.map(fu => fu.promise))
                            .then(results => {
                                // Add file paths to settings data
                                results.forEach(result => {
                                    settingsData[result.field] = result.path;
                                });
                                
                                // Send settings data to server
                                return fetch(formConfig.endpoint, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(settingsData)
                                });
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showToast(data.message || 'تم حفظ الإعدادات بنجاح', 'success');
                                } else {
                                    showToast(data.message || 'حدث خطأ أثناء حفظ الإعدادات', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showToast('حدث خطأ أثناء المعالجة', 'error');
                            });
                    });
                }
            });
        });
    </script>
</body>
</html>
