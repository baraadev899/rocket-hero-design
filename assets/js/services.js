
// DOM Elements
const servicesList = document.getElementById('servicesList');

// Fetch Services Details
async function fetchServicesDetails() {
    try {
        const response = await fetch('admin/api/services.php');
        const data = await response.json();
        
        if (data.success && data.data.length > 0) {
            return data.data;
        }
        return [];
    } catch (error) {
        console.error('Error fetching services:', error);
        return [];
    }
}

// Render Service Details
async function renderServicesDetails() {
    if (!servicesList) return;
    
    const services = await fetchServicesDetails();
    
    if (services.length > 0) {
        servicesList.innerHTML = services.map((service, index) => `
            <div class="service-detail-item ${index % 2 === 0 ? '' : 'reverse'}">
                <div class="service-detail-content">
                    <div class="service-icon">
                        <i class="${service.icon || 'fas fa-rocket'}"></i>
                    </div>
                    <h2 class="service-title">${service.title}</h2>
                    <p class="service-description">${service.description || service.short_description}</p>
                    <ul class="service-features">
                        ${service.features ? 
                            service.features.split(',').map(feature => `
                                <li><i class="fas fa-check-circle"></i> ${feature.trim()}</li>
                            `).join('') : 
                            '<li><i class="fas fa-check-circle"></i> خدمة احترافية</li>'
                        }
                    </ul>
                    <a href="contact.html" class="btn btn-primary">طلب الخدمة</a>
                </div>
                <div class="service-detail-image">
                    <img src="${service.image || 'assets/images/service-placeholder.jpg'}" alt="${service.title}">
                </div>
            </div>
        `).join('');
    } else {
        servicesList.innerHTML = '<p class="no-services">لا توجد خدمات متاحة حالياً</p>';
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', () => {
    renderServicesDetails();
});
