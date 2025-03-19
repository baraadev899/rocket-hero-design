
// DOM Elements
const navbarElement = document.querySelector('.navbar');
const menuToggle = document.querySelector('.mobile-menu-toggle');
const mobileMenu = document.querySelector('.mobile-menu');
const menuIcon = document.querySelector('.menu-icon');
const closeIcon = document.querySelector('.close-icon');
const contactForm = document.getElementById('contactForm');
const formMessage = document.getElementById('formMessage');
const toast = document.getElementById('toast');
const toastMessage = document.getElementById('toastMessage');
const toastClose = document.getElementById('toastClose');
const rocketContainer = document.getElementById('rocketContainer');
const codeParticlesCanvas = document.getElementById('codeParticles');

// Code particles
function setupCodeParticles() {
    if (!codeParticlesCanvas) return;
    
    const ctx = codeParticlesCanvas.getContext('2d');
    if (!ctx) return;
    
    // Set canvas size
    function resizeCanvas() {
        const parentElement = codeParticlesCanvas.parentElement;
        if (parentElement) {
            codeParticlesCanvas.width = parentElement.offsetWidth;
            codeParticlesCanvas.height = parentElement.offsetHeight;
        }
    }
    
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();
    
    // Code snippets
    const codeSnippets = [
        '{...}', '</>', '()', '[]', '{}', 'if()', 'for()', 'const', 'let', 'import', 'export',
        'function()', '=>', '===', '&&', '||', 'return', 'this', 'class', 'new', 'async', 'await'
    ];
    
    // Create particles
    const particleCount = Math.min(25, Math.floor(codeParticlesCanvas.width / 50));
    const particles = [];
    
    for (let i = 0; i < particleCount; i++) {
        particles.push({
            x: Math.random() * codeParticlesCanvas.width,
            y: codeParticlesCanvas.height + Math.random() * 100,
            size: 10 + Math.random() * 4,
            speed: 0.5 + Math.random() * 1,
            text: codeSnippets[Math.floor(Math.random() * codeSnippets.length)],
            opacity: 0.1 + Math.random() * 0.4
        });
    }
    
    // Animation
    function animate() {
        if (!codeParticlesCanvas || !ctx) return;
        
        ctx.clearRect(0, 0, codeParticlesCanvas.width, codeParticlesCanvas.height);
        
        particles.forEach(particle => {
            // Update particle position
            particle.y -= particle.speed;
            
            // Reset particle if it goes off screen
            if (particle.y < -20) {
                particle.y = codeParticlesCanvas.height + 10;
                particle.x = Math.random() * codeParticlesCanvas.width;
                particle.text = codeSnippets[Math.floor(Math.random() * codeSnippets.length)];
            }
            
            // Draw particle
            ctx.font = `${particle.size}px 'Courier New', monospace`;
            ctx.fillStyle = `rgba(255, 255, 255, ${particle.opacity})`;
            ctx.textAlign = 'center';
            ctx.fillText(particle.text, particle.x, particle.y);
        });
        
        requestAnimationFrame(animate);
    }
    
    animate();
}

// Rocket animation
function setupRocketAnimation() {
    if (!rocketContainer) return;
    
    function handleMouseMove(e) {
        const rect = rocketContainer.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        
        const deltaX = (e.clientX - centerX) / 30;
        const deltaY = (e.clientY - centerY) / 30;
        
        rocketContainer.style.transform = `translate(${deltaX}px, ${deltaY}px) rotate(${deltaX}deg)`;
    }
    
    document.addEventListener('mousemove', handleMouseMove);
}

// Navbar scroll effect
function handleScroll() {
    if (window.scrollY > 50) {
        navbarElement.classList.add('scrolled');
    } else {
        navbarElement.classList.remove('scrolled');
    }
}

// Toggle mobile menu
function toggleMobileMenu() {
    mobileMenu.classList.toggle('active');
    
    if (mobileMenu.classList.contains('active')) {
        menuIcon.style.display = 'none';
        closeIcon.style.display = 'block';
        document.body.style.overflow = 'hidden';
    } else {
        menuIcon.style.display = 'block';
        closeIcon.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Show toast notification
function showToast(message, type = 'success') {
    toastMessage.textContent = message;
    toast.classList.add('visible', type);
    
    setTimeout(() => {
        hideToast();
    }, 5000);
}

// Hide toast notification
function hideToast() {
    toast.classList.remove('visible');
    setTimeout(() => {
        toast.classList.remove('success', 'error');
    }, 300);
}

// Fetch services
async function fetchServices() {
    const servicesGrid = document.getElementById('servicesGrid');
    if (!servicesGrid) return;
    
    try {
        servicesGrid.innerHTML = '<div class="loading-spinner"><div class="spinner"></div></div>';
        
        const response = await fetch('api/services.php');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const services = await response.json();
        
        if (!Array.isArray(services) || services.length === 0) {
            servicesGrid.innerHTML = '<p class="error-message">لا توجد خدمات متاحة حالياً</p>';
            console.log('No services found or invalid response format:', services);
            return;
        }
        
        console.log('Fetched services:', services);
        
        servicesGrid.innerHTML = '';
        
        // Only display first 3 or 6 services based on screen size
        const displayCount = window.innerWidth >= 1024 ? Math.min(6, services.length) : Math.min(3, services.length);
        const servicesToDisplay = services.slice(0, displayCount);
        
        servicesToDisplay.forEach(service => {
            const serviceCard = document.createElement('div');
            serviceCard.className = 'service-card';
            
            serviceCard.innerHTML = `
                <div class="service-content">
                    <div class="service-icon">
                        <i class="fas ${service.icon || 'fa-rocket'}"></i>
                    </div>
                    <h3 class="service-title">${service.title}</h3>
                    <p class="service-description">${service.description || service.short_description || ''}</p>
                    <a href="services.html" class="service-link">
                        <span>اعرف المزيد</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            `;
            
            servicesGrid.appendChild(serviceCard);
        });
    } catch (error) {
        console.error('Error fetching services:', error);
        servicesGrid.innerHTML = '<p class="error-message">حدث خطأ أثناء تحميل الخدمات</p>';
    }
}

// Fetch projects
async function fetchProjects() {
    const projectsGrid = document.getElementById('projectsGrid');
    if (!projectsGrid) return;
    
    try {
        const response = await fetch('api/projects.php');
        const projects = await response.json();
        
        projectsGrid.innerHTML = '';
        
        // Only show 3 (or 6 on larger screens) most recent projects
        const filteredProjects = projects.slice(0, window.innerWidth >= 1024 ? 6 : 3);
        
        filteredProjects.forEach(project => {
            const projectCard = document.createElement('div');
            projectCard.className = 'project-card';
            
            projectCard.innerHTML = `
                <img src="${project.image}" alt="${project.title}" class="project-image">
                <div class="project-overlay">
                    <h3 class="project-title">${project.title}</h3>
                    <p class="project-category">${project.category}</p>
                    <a href="portfolio.html" class="project-link">
                        <span>عرض المشروع</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            `;
            
            projectsGrid.appendChild(projectCard);
        });
    } catch (error) {
        console.error('Error fetching projects:', error);
        projectsGrid.innerHTML = '<p class="error-message">حدث خطأ أثناء تحميل المشاريع</p>';
    }
}

// Fetch team members
async function fetchTeam() {
    const teamGrid = document.getElementById('teamGrid');
    if (!teamGrid) return;
    
    try {
        const response = await fetch('api/team.php');
        const team = await response.json();
        
        teamGrid.innerHTML = '';
        
        team.forEach(member => {
            const teamCard = document.createElement('div');
            teamCard.className = 'team-card';
            
            teamCard.innerHTML = `
                <div class="team-image-container">
                    <img src="${member.image}" alt="${member.name}" class="team-image">
                </div>
                <div class="team-content">
                    <h3 class="team-name">${member.name}</h3>
                    <p class="team-position">${member.position}</p>
                    <div class="team-social">
                        ${member.facebook ? `<a href="${member.facebook}" class="team-social-link"><i class="fab fa-facebook-f"></i></a>` : ''}
                        ${member.twitter ? `<a href="${member.twitter}" class="team-social-link"><i class="fab fa-twitter"></i></a>` : ''}
                        ${member.instagram ? `<a href="${member.instagram}" class="team-social-link"><i class="fab fa-instagram"></i></a>` : ''}
                        ${member.linkedin ? `<a href="${member.linkedin}" class="team-social-link"><i class="fab fa-linkedin-in"></i></a>` : ''}
                    </div>
                </div>
            `;
            
            teamGrid.appendChild(teamCard);
        });
    } catch (error) {
        console.error('Error fetching team:', error);
        teamGrid.innerHTML = '<p class="error-message">حدث خطأ أثناء تحميل فريق العمل</p>';
    }
}

// Handle contact form submission
function handleContactSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(contactForm);
    
    // Disable the submit button
    const submitButton = contactForm.querySelector('.submit-button');
    submitButton.disabled = true;
    
    fetch('api/contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('تم إرسال رسالتك بنجاح، سنتواصل معك قريباً', 'success');
            contactForm.reset();
        } else {
            showToast(data.message || 'حدث خطأ أثناء إرسال الرسالة', 'error');
        }
    })
    .catch(error => {
        console.error('Error submitting form:', error);
        showToast('حدث خطأ أثناء إرسال الرسالة', 'error');
    })
    .finally(() => {
        // Enable the submit button
        submitButton.disabled = false;
    });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    // Initialize components
    setupCodeParticles();
    setupRocketAnimation();
    
    // Fetch data
    fetchServices();
    fetchProjects();
    fetchTeam();
    
    // Event listeners
    window.addEventListener('scroll', handleScroll);
    
    if (menuToggle) {
        menuToggle.addEventListener('click', toggleMobileMenu);
    }
    
    if (contactForm) {
        contactForm.addEventListener('submit', handleContactSubmit);
    }
    
    if (toastClose) {
        toastClose.addEventListener('click', hideToast);
    }
    
    // Initial navbar state
    handleScroll();
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (mobileMenu && mobileMenu.classList.contains('active') && 
            !mobileMenu.contains(e.target) && 
            !menuToggle.contains(e.target)) {
            mobileMenu.classList.remove('active');
            menuIcon.style.display = 'block';
            closeIcon.style.display = 'none';
            document.body.style.overflow = '';
        }
    });
    
    // Set active menu item based on current page
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        const itemHref = item.getAttribute('href');
        if (itemHref === currentPage || 
            (currentPage === 'index.html' && itemHref === './') || 
            (currentPage === '' && itemHref === './' || itemHref === 'index.html')) {
            item.classList.add('active');
        }
    });
    
    const mobileMenuItems = document.querySelectorAll('.mobile-menu-item');
    mobileMenuItems.forEach(item => {
        const itemHref = item.getAttribute('href');
        if (itemHref === currentPage || 
            (currentPage === 'index.html' && itemHref === './') || 
            (currentPage === '' && itemHref === './' || itemHref === 'index.html')) {
            item.classList.add('active');
        }
    });
});
