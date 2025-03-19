
// DOM Elements
const header = document.getElementById('header');
const mobileMenuToggle = document.getElementById('mobileMenuToggle');
const mobileMenu = document.getElementById('mobileMenu');
const particlesCanvas = document.getElementById('particlesCanvas');
const rocketAnimation = document.getElementById('rocketAnimation');
const servicesGrid = document.getElementById('servicesGrid');
const projectsGrid = document.getElementById('projectsGrid');
const teamSlider = document.getElementById('teamSlider');
const faqAccordion = document.getElementById('faqAccordion');
const footerServicesList = document.getElementById('footerServicesList');
const footerContactInfo = document.getElementById('footerContactInfo');
const currentYearElement = document.getElementById('currentYear');

// Update current year
currentYearElement.textContent = new Date().getFullYear();

// Header scroll effect
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// Mobile Menu Toggle
mobileMenuToggle.addEventListener('click', () => {
    mobileMenuToggle.classList.toggle('active');
    mobileMenu.classList.toggle('active');
});

// Code Particles Animation
if (particlesCanvas) {
    const ctx = particlesCanvas.getContext('2d');
    const particles = [];
    const codeSnippets = [
        '{...}', '</>', '()', '[]', '{}', 'if()', 'for()', 'const', 'let', 'import', 'export',
        'function()', '=>', '===', '&&', '||', 'return', 'this', 'class', 'new', 'async', 'await'
    ];

    // Set canvas size
    function resizeCanvas() {
        particlesCanvas.width = particlesCanvas.parentElement.clientWidth;
        particlesCanvas.height = particlesCanvas.parentElement.clientHeight;
    }

    // Initialize particles
    function initParticles() {
        particles.length = 0;
        const particleCount = Math.min(25, Math.floor(particlesCanvas.width / 50));
        
        for (let i = 0; i < particleCount; i++) {
            particles.push({
                x: Math.random() * particlesCanvas.width,
                y: particlesCanvas.height + Math.random() * 100,
                size: 10 + Math.random() * 4,
                speed: 0.5 + Math.random() * 1,
                text: codeSnippets[Math.floor(Math.random() * codeSnippets.length)],
                opacity: 0.1 + Math.random() * 0.4
            });
        }
    }

    // Animate particles
    function animateParticles() {
        ctx.clearRect(0, 0, particlesCanvas.width, particlesCanvas.height);
        
        particles.forEach(particle => {
            // Update particle position
            particle.y -= particle.speed;
            
            // Reset particle if it goes off screen
            if (particle.y < -20) {
                particle.y = particlesCanvas.height + 10;
                particle.x = Math.random() * particlesCanvas.width;
                particle.text = codeSnippets[Math.floor(Math.random() * codeSnippets.length)];
            }
            
            // Draw particle
            ctx.font = `${particle.size}px 'Courier New', monospace`;
            ctx.fillStyle = `rgba(255, 255, 255, ${particle.opacity})`;
            ctx.textAlign = 'center';
            ctx.fillText(particle.text, particle.x, particle.y);
        });
        
        requestAnimationFrame(animateParticles);
    }

    // Initialize canvas
    window.addEventListener('resize', () => {
        resizeCanvas();
        initParticles();
    });
    
    resizeCanvas();
    initParticles();
    animateParticles();
}

// Rocket Animation Effect
if (rocketAnimation) {
    const rocket = rocketAnimation.querySelector('.rocket');
    
    // Mouse movement effect
    document.addEventListener('mousemove', (e) => {
        const rect = rocketAnimation.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        
        const deltaX = (e.clientX - centerX) / 30;
        const deltaY = (e.clientY - centerY) / 30;
        
        rocket.style.transform = `translate(${deltaX}px, ${deltaY}px) rotate(${deltaX}deg)`;
    });
}

// Fetch Services
async function fetchServices() {
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

// Render Services
async function renderServices() {
    if (!servicesGrid) return;
    
    const services = await fetchServices();
    
    if (services.length > 0) {
        servicesGrid.innerHTML = services.map(service => `
            <div class="service-card">
                <div class="service-icon">
                    <i class="${service.icon || 'fas fa-rocket'}"></i>
                </div>
                <h3 class="service-title">${service.title}</h3>
                <p class="service-description">${service.short_description}</p>
            </div>
        `).join('');
        
        // Update footer services list
        if (footerServicesList && services.length > 0) {
            footerServicesList.innerHTML = services.slice(0, 4).map(service => `
                <li><a href="services.html">${service.title}</a></li>
            `).join('');
        }
    } else {
        servicesGrid.innerHTML = '<p>لا توجد خدمات متاحة حالياً</p>';
    }
}

// Fetch Projects
async function fetchProjects() {
    try {
        const response = await fetch('admin/api/projects.php?featured=1');
        const data = await response.json();
        
        if (data.success && data.data.length > 0) {
            return data.data;
        }
        return [];
    } catch (error) {
        console.error('Error fetching projects:', error);
        return [];
    }
}

// Render Projects
async function renderProjects() {
    if (!projectsGrid) return;
    
    const projects = await fetchProjects();
    
    if (projects.length > 0) {
        projectsGrid.innerHTML = projects.map(project => `
            <div class="project-card">
                <img src="${project.image}" alt="${project.title}" class="project-image">
                <div class="project-content">
                    <span class="project-category">${project.category}</span>
                    <h3 class="project-title">${project.title}</h3>
                    <p class="project-description">${project.short_description}</p>
                    <a href="project-details.html?id=${project.id}" class="project-link">
                        عرض التفاصيل <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        `).join('');
    } else {
        projectsGrid.innerHTML = '<p>لا توجد مشاريع متاحة حالياً</p>';
    }
}

// Fetch Team Members
async function fetchTeam() {
    try {
        const response = await fetch('admin/api/team.php');
        const data = await response.json();
        
        if (data.success && data.data.length > 0) {
            return data.data;
        }
        return [];
    } catch (error) {
        console.error('Error fetching team:', error);
        return [];
    }
}

// Render Team Members
async function renderTeam() {
    if (!teamSlider) return;
    
    const team = await fetchTeam();
    
    if (team.length > 0) {
        teamSlider.innerHTML = team.map(member => `
            <div class="team-card">
                <div class="team-image-wrapper">
                    <img src="${member.image}" alt="${member.name}" class="team-image">
                </div>
                <div class="team-content">
                    <h3 class="team-name">${member.name}</h3>
                    <p class="team-role">${member.position}</p>
                    <div class="team-social">
                        ${member.facebook ? `<a href="${member.facebook}" target="_blank"><i class="fab fa-facebook-f"></i></a>` : ''}
                        ${member.twitter ? `<a href="${member.twitter}" target="_blank"><i class="fab fa-twitter"></i></a>` : ''}
                        ${member.linkedin ? `<a href="${member.linkedin}" target="_blank"><i class="fab fa-linkedin-in"></i></a>` : ''}
                        ${member.instagram ? `<a href="${member.instagram}" target="_blank"><i class="fab fa-instagram"></i></a>` : ''}
                    </div>
                </div>
            </div>
        `).join('');
    } else {
        teamSlider.innerHTML = '<p>لا يوجد أعضاء فريق حالياً</p>';
    }
}

// Fetch FAQs
async function fetchFAQs() {
    try {
        const response = await fetch('admin/api/faqs.php');
        const data = await response.json();
        
        if (data.success && data.data.length > 0) {
            return data.data;
        }
        return [];
    } catch (error) {
        console.error('Error fetching FAQs:', error);
        return [];
    }
}

// Render FAQs
async function renderFAQs() {
    if (!faqAccordion) return;
    
    const faqs = await fetchFAQs();
    
    if (faqs.length > 0) {
        faqAccordion.innerHTML = faqs.map((faq, index) => `
            <div class="faq-item" data-index="${index}">
                <div class="faq-question">${faq.question}</div>
                <div class="faq-answer">${faq.answer}</div>
            </div>
        `).join('');
        
        // Add click event to FAQ items
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            item.querySelector('.faq-question').addEventListener('click', () => {
                faqItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                    }
                });
                item.classList.toggle('active');
            });
        });
    } else {
        faqAccordion.innerHTML = '<p>لا توجد أسئلة شائعة حالياً</p>';
    }
}

// Fetch Settings
async function fetchSettings() {
    try {
        const response = await fetch('admin/api/settings.php');
        const data = await response.json();
        
        if (data.success && data.data) {
            return data.data;
        }
        return null;
    } catch (error) {
        console.error('Error fetching settings:', error);
        return null;
    }
}

// Update Settings
async function updateSettings() {
    const settings = await fetchSettings();
    
    if (settings) {
        // Update title
        document.title = settings.site_title || 'روكيت - شركة تطوير ويب وتطبيقات';
        
        // Update site name in navbar and footer
        const siteNames = document.querySelectorAll('.logo-text');
        siteNames.forEach(element => {
            element.textContent = settings.site_title || 'روكيت';
        });
        
        // Update contact info in footer
        if (footerContactInfo) {
            footerContactInfo.innerHTML = `
                <li><i class="fas fa-map-marker-alt"></i> <span>${settings.address || 'القاهرة، مصر'}</span></li>
                <li><i class="fas fa-phone"></i> <span>${settings.phone || '+20 123 456 7890'}</span></li>
                <li><i class="fas fa-envelope"></i> <span>${settings.email || 'info@rocketagency.com'}</span></li>
            `;
        }
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', () => {
    updateSettings();
    renderServices();
    renderProjects();
    renderTeam();
    renderFAQs();
});
