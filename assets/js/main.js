
// DOM Elements
const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
const mobileMenu = document.querySelector('.mobile-menu');
const navbar = document.querySelector('.navbar');
const servicesContainer = document.getElementById('services-container');
const projectsContainer = document.getElementById('projects-container');

// Toggle Mobile Menu
if (mobileMenuToggle) {
    mobileMenuToggle.addEventListener('click', function() {
        mobileMenu.classList.toggle('active');
        this.classList.toggle('active');
        document.body.classList.toggle('menu-open');
    });
}

// Close menu when clicking a link in mobile menu
if (mobileMenu) {
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', function() {
            mobileMenu.classList.remove('active');
            mobileMenuToggle.classList.remove('active');
            document.body.classList.remove('menu-open');
        });
    });
}

// Handle navbar background on scroll
function handleScroll() {
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
}

// Add scroll event listener
window.addEventListener('scroll', handleScroll);

// Initialize navbar state on page load
handleScroll();

// Fetch and display services on homepage
async function fetchHomeServices() {
    if (!servicesContainer) return;

    try {
        const response = await fetch('api/services.php');
        const services = await response.json();
        
        if (Array.isArray(services) && services.length > 0) {
            // Display max 3 services
            const limitedServices = services.slice(0, 3);
            
            servicesContainer.innerHTML = limitedServices.map(service => `
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas ${service.icon || 'fa-rocket'}"></i>
                    </div>
                    <h3>${service.title}</h3>
                    <p>${truncateText(service.description, 120)}</p>
                    <a href="services.html" class="service-link">اقرأ المزيد <i class="fas fa-arrow-left"></i></a>
                </div>
            `).join('');
        } else {
            servicesContainer.innerHTML = '<p class="no-data">لا توجد خدمات متاحة حالياً</p>';
        }
    } catch (error) {
        console.error('Error fetching services:', error);
        servicesContainer.innerHTML = '<p class="error-message">حدث خطأ أثناء تحميل الخدمات</p>';
    }
}

// Fetch and display featured projects on homepage
async function fetchHomeProjects() {
    if (!projectsContainer) return;

    try {
        const response = await fetch('admin/api/projects.php?featured=1');
        let projects = [];
        
        const data = await response.json();
        if (data.success && Array.isArray(data.data)) {
            projects = data.data;
        }
        
        if (projects.length > 0) {
            // Display max 3 projects
            const limitedProjects = projects.slice(0, 3);
            
            projectsContainer.innerHTML = limitedProjects.map(project => `
                <div class="project-card">
                    <div class="project-image">
                        <img src="${project.image}" alt="${project.title}">
                        <div class="project-overlay">
                            <div class="project-category">${project.category}</div>
                            <a href="portfolio.html" class="project-link">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                    <div class="project-info">
                        <h3>${project.title}</h3>
                        <p>${truncateText(project.description, 100)}</p>
                    </div>
                </div>
            `).join('');
        } else {
            projectsContainer.innerHTML = '<p class="no-data">لا توجد مشاريع متاحة حالياً</p>';
        }
    } catch (error) {
        console.error('Error fetching projects:', error);
        projectsContainer.innerHTML = '<p class="error-message">حدث خطأ أثناء تحميل المشاريع</p>';
    }
}

// Helper function to truncate text
function truncateText(text, maxLength) {
    if (!text) return '';
    return text.length > maxLength ? text.slice(0, maxLength) + '...' : text;
}

// Initialize homepage components if they exist
document.addEventListener('DOMContentLoaded', function() {
    // Load services for homepage
    fetchHomeServices();
    
    // Load projects for homepage
    fetchHomeProjects();
});
