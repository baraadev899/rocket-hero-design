
// Wait for DOM to be loaded
document.addEventListener('DOMContentLoaded', function() {
  // Initialize the code particles animation
  initCodeParticles();
  
  // Initialize the rocket animation
  initRocketAnimation();
  
  // Initialize theme
  initTheme();
    
  // Mobile menu toggle
  initMobileMenu();
    
  // Scroll animations
  initScrollAnimations();
    
  // Service cards hover effect
  initServiceCards();
  
  // Initialize FAQ toggles if on services page
  if (document.querySelector('.faq-item')) {
    initFaqToggles();
  }
  
  // Initialize portfolio filters if on portfolio page
  if (document.querySelector('.portfolio-filters')) {
    initPortfolioFilters();
  }
  
  // Initialize contact form if on contact page
  if (document.querySelector('.contact-form')) {
    initContactForm();
  }
  
  // Initialize team member hover effects if on team page
  if (document.querySelector('.team-member')) {
    initTeamMemberEffects();
  }
  
  // Initialize comments section if it exists
  if (document.querySelector('.comments-section')) {
    initCommentsSection();
  }
  
  // Add smooth scrolling for anchor links
  initSmoothScrolling();
  
  // Initialize dynamic copyright year
  updateCopyrightYear();
  
  // Initialize parallax effect
  initParallaxEffect();
});

// Theme Toggle Functionality
function initTheme() {
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;
    const icon = themeToggle.querySelector('i');
    
    // Check for saved theme preference or use preferred-color-scheme
    const savedTheme = localStorage.getItem('theme');
    
    if (savedTheme === 'light') {
        body.classList.remove('dark-mode');
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    } else {
        // Default to dark mode
        body.classList.add('dark-mode');
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    }
    
    // Theme toggle button event listener
    themeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            localStorage.setItem('theme', 'light');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
    });
}

// Mobile Menu Functionality
function initMobileMenu() {
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mainNav = document.querySelector('.main-nav');
    const navLinks = document.querySelectorAll('.nav-link');
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            mainNav.classList.toggle('active');
            document.body.classList.toggle('no-scroll');
        });
        
        // Close mobile menu when clicking on a link
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenuToggle.classList.remove('active');
                mainNav.classList.remove('active');
                document.body.classList.remove('no-scroll');
            });
        });
    }
}

// Scroll Animations
function initScrollAnimations() {
    const sections = document.querySelectorAll('section');
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    // Function to check if element is in viewport
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.8 &&
            rect.bottom >= 0
        );
    }
    
    // Initial check for elements in viewport
    sections.forEach(section => {
        if (isInViewport(section)) {
            section.classList.add('in-view');
        }
    });
    
    animatedElements.forEach(element => {
        if (isInViewport(element)) {
            element.classList.add('visible');
        }
    });
    
    // Check on scroll
    window.addEventListener('scroll', function() {
        sections.forEach(section => {
            if (isInViewport(section)) {
                section.classList.add('in-view');
            }
        });
        
        animatedElements.forEach(element => {
            if (isInViewport(element)) {
                element.classList.add('visible');
            }
        });
    });
}

// Service Cards Hover Effects
function initServiceCards() {
    const serviceCards = document.querySelectorAll('.service-card');
    
    serviceCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('hovered');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('hovered');
        });
    });
}

// Smooth Scrolling
function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const headerOffset = 80;
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Update Copyright Year
function updateCopyrightYear() {
    const footerYear = document.querySelector('.footer-bottom p');
    if (footerYear) {
        const currentYear = new Date().getFullYear();
        footerYear.innerHTML = footerYear.innerHTML.replace('2023', currentYear);
    }
}

// Parallax Effect
function initParallaxEffect() {
    window.addEventListener('scroll', function() {
        const scrollPosition = window.pageYOffset;
        
        // Apply parallax effect to hero section
        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            heroSection.style.backgroundPositionY = scrollPosition * 0.5 + 'px';
        }
    });
}

// Code Particles Animation
function initCodeParticles() {
    const canvas = document.getElementById('codeParticles');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    
    // Set canvas to full parent width/height
    function resizeCanvas() {
        const parent = canvas.parentElement;
        canvas.width = parent.clientWidth;
        canvas.height = parent.clientHeight;
    }
    
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();
    
    // Code snippets for particles
    const codeSnippets = [
        '{...}', '</>', '()', '[]', '{}', 'if()', 'for()', 'const', 'let', 'import', 'export',
        'function()', '=>', '===', '&&', '||', 'return', 'this', 'class', 'new', 'async', 'await'
    ];
    
    // Initialize particles
    const particleCount = Math.min(25, Math.floor(canvas.width / 50));
    const particles = [];
    
    for (let i = 0; i < particleCount; i++) {
        particles.push({
            x: Math.random() * canvas.width,
            y: canvas.height + Math.random() * 100,
            size: 10 + Math.random() * 4,
            speed: 0.5 + Math.random() * 1,
            text: codeSnippets[Math.floor(Math.random() * codeSnippets.length)],
            opacity: 0.1 + Math.random() * 0.4
        });
    }
    
    // Animation loop
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        particles.forEach(particle => {
            // Update particle position
            particle.y -= particle.speed;
            
            // Reset particle if it goes off screen
            if (particle.y < -20) {
                particle.y = canvas.height + 10;
                particle.x = Math.random() * canvas.width;
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

// Rocket Animation
function initRocketAnimation() {
    const rocket = document.getElementById('rocket');
    if (!rocket) return;
    
    // Float animation
    function floatAnimation() {
        rocket.style.animation = 'float 3s ease-in-out infinite';
    }
    
    floatAnimation();
    
    // Make rocket follow mouse
    document.addEventListener('mousemove', e => {
        const rocketRect = rocket.getBoundingClientRect();
        const centerX = rocketRect.left + rocketRect.width / 2;
        const centerY = rocketRect.top + rocketRect.height / 2;
        
        const deltaX = (e.clientX - centerX) / 30;
        const deltaY = (e.clientY - centerY) / 30;
        
        rocket.style.transform = `translate(${deltaX}px, ${deltaY}px) rotate(${deltaX}deg)`;
    });
}

// FAQ Toggles
function initFaqToggles() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        question.addEventListener('click', () => {
            item.classList.toggle('active');
            
            // Close other items
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });
        });
    });
}

// Portfolio Filters
function initPortfolioFilters() {
    const filterButtons = document.querySelectorAll('.filter-button');
    const portfolioItems = document.querySelectorAll('.portfolio-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Reset active state
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            const filterValue = button.dataset.filter;
            
            portfolioItems.forEach(item => {
                if (filterValue === 'all' || item.dataset.category === filterValue) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
}

// Contact Form with improved validation and feedback
function initContactForm() {
    const contactForm = document.querySelector('.contact-form');
    if (!contactForm) return;
    
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(contactForm);
        const formValues = Object.fromEntries(formData.entries());
        
        // Enhanced validation
        let isValid = true;
        let firstInvalidField = null;
        
        contactForm.querySelectorAll('[required]').forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('invalid');
                
                // Store the first invalid field to focus on it
                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
            } else {
                field.classList.remove('invalid');
            }
            
            // Email validation if it's an email field
            if (field.type === 'email' && field.value.trim()) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(field.value)) {
                    isValid = false;
                    field.classList.add('invalid');
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                }
            }
        });
        
        if (!isValid) {
            // Focus on the first invalid field
            if (firstInvalidField) {
                firstInvalidField.focus();
            }
            
            // Show validation message
            const validationMessage = document.createElement('div');
            validationMessage.className = 'validation-message error';
            validationMessage.textContent = 'يرجى ملء جميع الحقول المطلوبة بشكل صحيح';
            
            // Remove any existing validation messages
            const existingMessage = contactForm.querySelector('.validation-message');
            if (existingMessage) {
                existingMessage.remove();
            }
            
            // Add the new message
            contactForm.insertBefore(validationMessage, contactForm.firstChild);
            
            // Remove the message after 4 seconds
            setTimeout(() => {
                validationMessage.classList.add('fade-out');
                setTimeout(() => {
                    validationMessage.remove();
                }, 300);
            }, 4000);
            
            return;
        }
        
        // Show loading indicator
        const submitButton = contactForm.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.innerHTML = '<span class="loading-spinner"></span> جاري الإرسال...';
        submitButton.disabled = true;
        
        // Simulate server request - In a real application, you would send this to a backend
        setTimeout(() => {
            // For demo purposes, just show success message
            contactForm.innerHTML = `
                <div class="form-success">
                    <div class="success-icon">✓</div>
                    <h3>شكراً لتواصلك معنا!</h3>
                    <p>تم استلام رسالتك بنجاح وسنرد عليك في أقرب وقت ممكن.</p>
                </div>
            `;
        }, 1500);
    });
}

// Team Member Effects
function initTeamMemberEffects() {
    const teamMembers = document.querySelectorAll('.team-member');
    
    teamMembers.forEach(member => {
        member.addEventListener('mouseenter', function() {
            this.classList.add('hovered');
        });
        
        member.addEventListener('mouseleave', function() {
            this.classList.remove('hovered');
        });
    });
}

// Comments Section Management
function initCommentsSection() {
    const commentsForm = document.querySelector('.comments-form');
    const commentsList = document.querySelector('.comments-list');
    
    if (!commentsForm || !commentsList) return;
    
    commentsForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const name = commentsForm.querySelector('#comment-name').value;
        const email = commentsForm.querySelector('#comment-email').value;
        const content = commentsForm.querySelector('#comment-content').value;
        
        if (!name || !email || !content) {
            alert('يرجى ملء جميع الحقول المطلوبة');
            return;
        }
        
        // Create new comment
        const newComment = document.createElement('div');
        newComment.className = 'comment-item';
        
        // Get current date
        const now = new Date();
        const dateString = `${now.getDate()}/${now.getMonth() + 1}/${now.getFullYear()}`;
        
        newComment.innerHTML = `
            <div class="comment-author">
                <div class="author-image">
                    <div class="default-avatar">${name.charAt(0)}</div>
                </div>
                <div class="author-info">
                    <h4 class="author-name">${name}</h4>
                    <p class="comment-date">${dateString}</p>
                </div>
            </div>
            <div class="comment-content">
                <p>${content}</p>
            </div>
        `;
        
        // Add the comment to the list
        commentsList.appendChild(newComment);
        
        // Reset form
        commentsForm.reset();
        
        // Show success message
        const successMessage = document.createElement('div');
        successMessage.className = 'comment-success-message';
        successMessage.textContent = 'تم إضافة تعليقك بنجاح';
        commentsForm.appendChild(successMessage);
        
        // Remove success message after 3 seconds
        setTimeout(() => {
            successMessage.remove();
        }, 3000);
    });
}
