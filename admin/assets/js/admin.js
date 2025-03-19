
// Toggle sidebar on mobile
document.addEventListener('DOMContentLoaded', function() {
    // Create toggle button for mobile
    const createToggleButton = () => {
        if (window.innerWidth <= 576) {
            // Check if button already exists
            if (!document.querySelector('.toggle-sidebar')) {
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'toggle-sidebar';
                toggleBtn.innerHTML = '☰';
                document.body.appendChild(toggleBtn);
                
                // Add event listener
                toggleBtn.addEventListener('click', function() {
                    const sidebar = document.querySelector('.sidebar');
                    sidebar.classList.toggle('show');
                });
            }
        } else {
            // Remove button if window is resized
            const existingBtn = document.querySelector('.toggle-sidebar');
            if (existingBtn) {
                existingBtn.remove();
            }
        }
    };
    
    // Initialize on load
    createToggleButton();
    
    // Update on resize
    window.addEventListener('resize', createToggleButton);
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 576) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('.toggle-sidebar');
            
            if (sidebar && sidebar.classList.contains('show') && 
                !sidebar.contains(event.target) && 
                toggleBtn && !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    
                    // Add error message if not exists
                    let errorMsg = field.parentNode.querySelector('.error-message');
                    if (!errorMsg) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'error-message';
                        errorMsg.textContent = 'هذا الحقل مطلوب';
                        field.parentNode.appendChild(errorMsg);
                    }
                } else {
                    field.classList.remove('error');
                    const errorMsg = field.parentNode.querySelector('.error-message');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
});

// Show toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    const content = document.createElement('div');
    content.className = 'toast-content';
    
    const icon = document.createElement('div');
    icon.className = 'toast-icon';
    
    const iconElement = document.createElement('i');
    iconElement.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    icon.appendChild(iconElement);
    
    const messageElement = document.createElement('div');
    messageElement.className = 'toast-message';
    messageElement.textContent = message;
    
    const closeBtn = document.createElement('button');
    closeBtn.className = 'toast-close';
    closeBtn.innerHTML = '<i class="fas fa-times"></i>';
    
    content.appendChild(icon);
    content.appendChild(messageElement);
    
    toast.appendChild(content);
    toast.appendChild(closeBtn);
    
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Auto hide after 5 seconds
    const hideTimeout = setTimeout(() => {
        hideToast(toast);
    }, 5000);
    
    // Close button event
    closeBtn.addEventListener('click', () => {
        clearTimeout(hideTimeout);
        hideToast(toast);
    });
}

function hideToast(toast) {
    toast.classList.remove('show');
    
    // Remove from DOM after animation
    setTimeout(() => {
        toast.remove();
    }, 300);
}

// AJAX Helper
function makeAjaxRequest(url, method = 'GET', data = null) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        
        if (method === 'POST' && !(data instanceof FormData)) {
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        }
        
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    resolve(response);
                } catch (e) {
                    resolve(xhr.responseText);
                }
            } else {
                reject({
                    status: xhr.status,
                    statusText: xhr.statusText
                });
            }
        };
        
        xhr.onerror = function() {
            reject({
                status: xhr.status,
                statusText: xhr.statusText
            });
        };
        
        if (data instanceof FormData) {
            xhr.send(data);
        } else if (data) {
            let formData = '';
            for (const key in data) {
                if (formData !== '') {
                    formData += '&';
                }
                formData += encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
            }
            xhr.send(formData);
        } else {
            xhr.send();
        }
    });
}
