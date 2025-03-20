
// Services Page JavaScript

document.addEventListener('DOMContentLoaded', () => {
    const servicesContainer = document.getElementById('servicesContainer');
    const serviceDetails = document.getElementById('serviceDetails');
    
    // Load all services
    async function loadServices() {
        if (!servicesContainer) return;
        
        try {
            servicesContainer.innerHTML = '<div class="loading-spinner"></div>';
            
            const response = await fetch('api/services.php');
            const services = await response.json();
            
            if (!Array.isArray(services) || services.length === 0) {
                servicesContainer.innerHTML = '<p class="text-center">لا توجد خدمات للعرض</p>';
                return;
            }
            
            let html = '';
            
            for (const service of services) {
                const icon = service.icon || 'fa-rocket';
                
                html += `
                    <div class="service-card" data-service-id="${service.id}">
                        <div class="service-icon">
                            <i class="fas ${icon}"></i>
                        </div>
                        <h3>${service.title}</h3>
                        <p>${service.description.substring(0, 120)}${service.description.length > 120 ? '...' : ''}</p>
                        <a href="javascript:void(0)" class="service-link service-details-link" data-service-id="${service.id}">
                            التفاصيل <i class="fas fa-long-arrow-alt-left"></i>
                        </a>
                    </div>
                `;
            }
            
            servicesContainer.innerHTML = html;
            
            // Add event listeners to service cards
            document.querySelectorAll('.service-details-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const serviceId = this.getAttribute('data-service-id');
                    showServiceDetails(serviceId, services);
                });
            });
            
        } catch (error) {
            console.error('Error loading services:', error);
            servicesContainer.innerHTML = '<p class="text-center">حدث خطأ أثناء تحميل الخدمات</p>';
        }
    }
    
    // Show service details
    function showServiceDetails(serviceId, services) {
        if (!serviceDetails) return;
        
        const service = services.find(s => s.id == serviceId);
        
        if (!service) {
            console.error('Service not found:', serviceId);
            return;
        }
        
        const icon = service.icon || 'fa-rocket';
        const imageSrc = service.image || 'assets/images/service-placeholder.jpg';
        
        let html = `
            <div class="service-details-header">
                <button id="closeServiceDetails" class="close-btn">
                    <i class="fas fa-times"></i>
                </button>
                <h2>${service.title}</h2>
            </div>
            <div class="service-details-content">
                <div class="service-details-icon">
                    <i class="fas ${icon}"></i>
                </div>
                ${service.image ? `
                <div class="service-details-image">
                    <img src="${imageSrc}" alt="${service.title}">
                </div>` : ''}
                <div class="service-details-description">
                    <p>${service.description}</p>
                </div>
                <a href="contact.html" class="btn btn-primary">تواصل معنا الآن</a>
            </div>
        `;
        
        serviceDetails.innerHTML = html;
        serviceDetails.classList.add('active');
        document.body.classList.add('overlay-active');
        
        // Close button event
        document.getElementById('closeServiceDetails').addEventListener('click', () => {
            serviceDetails.classList.remove('active');
            document.body.classList.remove('overlay-active');
        });
        
        // Close on overlay click
        serviceDetails.addEventListener('click', (e) => {
            if (e.target === serviceDetails) {
                serviceDetails.classList.remove('active');
                document.body.classList.remove('overlay-active');
            }
        });
    }
    
    // Initialize
    loadServices();
});
