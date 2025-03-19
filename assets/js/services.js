
// DOM Elements
const servicesList = document.getElementById('servicesList');

// Fetch Services Details
async function fetchServicesDetails() {
    try {
        console.log('Fetching services details...');
        // Show loading spinner
        if (servicesList) {
            servicesList.innerHTML = '<div class="loading-spinner"><div class="spinner"></div></div>';
        }
        
        // Using the regular services API (not admin API)
        const response = await fetch('api/services.php');
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Services data received:', data);
        
        if (Array.isArray(data) && data.length > 0) {
            return data;
        } else if (data && data.data && Array.isArray(data.data) && data.data.length > 0) {
            // Handle case where data is wrapped in a data property (from admin API)
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
    console.log('Services to render:', services);
    
    if (services.length > 0) {
        servicesList.innerHTML = services.map((service, index) => `
            <div class="service-detail-item ${index % 2 === 0 ? '' : 'reverse'}">
                <div class="service-detail-content">
                    <div class="service-icon">
                        <i class="fas ${service.icon || 'fa-rocket'}"></i>
                    </div>
                    <h2 class="service-title">${service.title}</h2>
                    <p class="service-description">${service.description || service.short_description || ''}</p>
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
    console.log('Services page initialized');
    renderServicesDetails();
});
