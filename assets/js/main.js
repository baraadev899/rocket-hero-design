
// Main JavaScript File for Rocket Agency

// DOM Elements
const body = document.querySelector('body');
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const mobileMenu = document.getElementById('mobileMenu');
const servicesContainer = document.getElementById('servicesContainer');
const featuredProjectsContainer = document.getElementById('featuredProjectsContainer');
const teamContainer = document.getElementById('teamContainer');
const testimonialSlider = document.querySelector('.testimonial-slider');
const counterElements = document.querySelectorAll('.counter');
const yearElement = document.getElementById('currentYear');

// Set current year in footer
if (yearElement) {
    yearElement.textContent = new Date().getFullYear();
}

// Mobile Menu Toggle
if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
        body.classList.toggle('menu-open');
        
        // Toggle aria-expanded for accessibility
        const expanded = mobileMenuBtn.getAttribute('aria-expanded') === 'true';
        mobileMenuBtn.setAttribute('aria-expanded', !expanded);
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (mobileMenu.classList.contains('active') && 
            !mobileMenu.contains(e.target) && 
            !mobileMenuBtn.contains(e.target)) {
            mobileMenu.classList.remove('active');
            body.classList.remove('menu-open');
            mobileMenuBtn.setAttribute('aria-expanded', 'false');
        }
    });
}

// Load Services
async function loadServices() {
    if (!servicesContainer) return;
    
    try {
        servicesContainer.innerHTML = '<div class="loading-spinner"><div class="spinner"></div></div>';
        
        const response = await fetch('admin/api/services.php');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        const services = Array.isArray(data) ? data : (data.data || []);
        
        if (!services.length) {
            servicesContainer.innerHTML = '<p class="text-center">لا توجد خدمات للعرض</p>';
            return;
        }
        
        let html = '';
        // Take only first 6 services for homepage
        const homeServices = services.slice(0, 6);
        
        for (const service of homeServices) {
            const icon = service.icon || 'fa-rocket';
            
            html += `
                <div class="service-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="service-icon">
                        <i class="fas ${icon}"></i>
                    </div>
                    <h3>${service.title}</h3>
                    <p>${service.description.substring(0, 120)}${service.description.length > 120 ? '...' : ''}</p>
                    <a href="services.html" class="service-link">اقرأ المزيد <i class="fas fa-long-arrow-alt-left"></i></a>
                </div>
            `;
        }
        
        servicesContainer.innerHTML = html;
    } catch (error) {
        console.error('Error loading services:', error);
        servicesContainer.innerHTML = '<p class="text-center">حدث خطأ أثناء تحميل الخدمات</p>';
    }
}

// Load Featured Projects
async function loadFeaturedProjects() {
    if (!featuredProjectsContainer) return;
    
    try {
        featuredProjectsContainer.innerHTML = '<div class="loading-spinner"><div class="spinner"></div></div>';
        
        const response = await fetch('admin/api/projects.php?featured=1');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        const projects = data.success ? data.data : [];
        
        if (!projects.length) {
            featuredProjectsContainer.innerHTML = '<p class="text-center">لا توجد مشاريع مميزة للعرض</p>';
            return;
        }
        
        let html = '';
        
        for (const project of projects) {
            html += `
                <div class="project-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="project-img">
                        <img src="${project.image || 'assets/images/project-placeholder.jpg'}" alt="${project.title}">
                    </div>
                    <div class="project-content">
                        <span class="project-category">${project.category}</span>
                        <h3>${project.title}</h3>
                        <a href="project.html?id=${project.id}" class="btn btn-outline">عرض المشروع</a>
                    </div>
                </div>
            `;
        }
        
        featuredProjectsContainer.innerHTML = html;
    } catch (error) {
        console.error('Error loading projects:', error);
        featuredProjectsContainer.innerHTML = '<p class="text-center">حدث خطأ أثناء تحميل المشاريع</p>';
    }
}

// Load Team Members
async function loadTeamMembers() {
    if (!teamContainer) return;
    
    try {
        teamContainer.innerHTML = '<div class="loading-spinner"><div class="spinner"></div></div>';
        
        const response = await fetch('admin/api/team.php');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        const team = Array.isArray(data) ? data : (data.data || []);
        
        if (!team.length) {
            teamContainer.innerHTML = '<p class="text-center">لا يوجد أعضاء فريق للعرض</p>';
            return;
        }
        
        let html = '';
        
        for (const member of team) {
            html += `
                <div class="team-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="team-img">
                        <img src="${member.image || 'assets/images/team-placeholder.jpg'}" alt="${member.name}">
                    </div>
                    <div class="team-content">
                        <h3>${member.name}</h3>
                        <p>${member.position}</p>
                        <div class="team-social">
                            ${member.email ? `<a href="mailto:${member.email}" aria-label="Email"><i class="fas fa-envelope"></i></a>` : ''}
                            ${member.twitter ? `<a href="${member.twitter}" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>` : ''}
                            ${member.linkedin ? `<a href="${member.linkedin}" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>` : ''}
                            ${member.instagram ? `<a href="${member.instagram}" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>` : ''}
                        </div>
                    </div>
                </div>
            `;
        }
        
        teamContainer.innerHTML = html;
    } catch (error) {
        console.error('Error loading team:', error);
        teamContainer.innerHTML = '<p class="text-center">حدث خطأ أثناء تحميل فريق العمل</p>';
    }
}

// Animate counters
function animateCounters() {
    if (!counterElements.length) return;
    
    const options = {
        threshold: 0.7
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-target'));
                const duration = 2000; // ms
                const increment = target / (duration / 16); // 60fps
                
                let current = 0;
                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.textContent = Math.ceil(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                };
                
                updateCounter();
                observer.unobserve(counter);
            }
        });
    }, options);
    
    counterElements.forEach(counter => {
        observer.observe(counter);
    });
}

// Initialize Testimonial Slider
function initTestimonialSlider() {
    if (!testimonialSlider) return;
    
    let currentSlide = 0;
    const slides = testimonialSlider.querySelectorAll('.testimonial-item');
    const totalSlides = slides.length;
    
    if (totalSlides <= 1) return;
    
    // Create navigation
    const navContainer = document.createElement('div');
    navContainer.className = 'testimonial-nav';
    
    const prevBtn = document.createElement('button');
    prevBtn.className = 'nav-btn prev-btn';
    prevBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
    prevBtn.setAttribute('aria-label', 'Previous testimonial');
    
    const nextBtn = document.createElement('button');
    nextBtn.className = 'nav-btn next-btn';
    nextBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
    nextBtn.setAttribute('aria-label', 'Next testimonial');
    
    navContainer.appendChild(prevBtn);
    navContainer.appendChild(nextBtn);
    testimonialSlider.appendChild(navContainer);
    
    // Show first slide
    slides[0].classList.add('active');
    
    // Next button click
    nextBtn.addEventListener('click', () => {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % totalSlides;
        slides[currentSlide].classList.add('active');
    });
    
    // Prev button click
    prevBtn.addEventListener('click', () => {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        slides[currentSlide].classList.add('active');
    });
    
    // Auto slide
    let interval = setInterval(() => {
        nextBtn.click();
    }, 5000);
    
    // Pause on hover
    testimonialSlider.addEventListener('mouseenter', () => {
        clearInterval(interval);
    });
    
    testimonialSlider.addEventListener('mouseleave', () => {
        interval = setInterval(() => {
            nextBtn.click();
        }, 5000);
    });
}

// Back to top button functionality
function initBackToTop() {
    const backToTopButton = document.querySelector('.back-to-top');
    if (!backToTopButton) return;
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopButton.classList.add('active');
        } else {
            backToTopButton.classList.remove('active');
        }
    });
    
    backToTopButton.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Initialize AOS animation library if it exists
function initAOS() {
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100,
            delay: 0
        });
    }
}

// Handle dark mode toggle
function initDarkMode() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (!darkModeToggle) return;
    
    // Check for saved dark mode preference
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    
    // Set initial dark mode state
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
        darkModeToggle.checked = true;
    }
    
    // Toggle dark mode on change
    darkModeToggle.addEventListener('change', () => {
        if (darkModeToggle.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'true');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'false');
        }
    });
}

// Document ready function
document.addEventListener('DOMContentLoaded', () => {
    // Initialize UI components
    initTestimonialSlider();
    animateCounters();
    initBackToTop();
    initAOS();
    initDarkMode();
    
    // Load data
    loadServices();
    loadFeaturedProjects();
    loadTeamMembers();
    
    // Add smooth scrolling to all links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const target = document.querySelector(this.getAttribute('href'));
            if (!target) return;
            
            window.scrollTo({
                top: target.offsetTop - 80,
                behavior: 'smooth'
            });
        });
    });
});
