
// Wait for DOM to be loaded
document.addEventListener('DOMContentLoaded', function() {
  // Initialize the navbar
  initNavbar();
  // Initialize FAQ toggles
  initFaq();
  // Initialize animation on scroll
  initAnimationOnScroll();
});

// Initialize Navbar
function initNavbar() {
  const navbar = document.getElementById('navbar');
  const menuToggle = document.getElementById('menuToggle');
  const navMenu = document.getElementById('navMenu');
  
  // Handle scroll effect for navbar
  window.addEventListener('scroll', function() {
    if (window.scrollY > 50) {
      navbar.classList.add('navbar-scrolled');
    } else {
      navbar.classList.remove('navbar-scrolled');
    }
  });
  
  // Handle menu toggle for mobile
  if (menuToggle && navMenu) {
    menuToggle.addEventListener('click', function() {
      navMenu.classList.toggle('nav-menu-active');
      menuToggle.classList.toggle('active');
    });
    
    // Close menu when clicking on a nav link on mobile
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', function() {
        navMenu.classList.remove('nav-menu-active');
        menuToggle.classList.remove('active');
      });
    });
  }
  
  // Set active link based on current page with improved path detection
  const currentPath = window.location.pathname;
  const pageName = currentPath.split('/').pop() || 'index.html';
  
  document.querySelectorAll('.nav-link').forEach(link => {
    const linkHref = link.getAttribute('href');
    
    // Check if the current page matches the link or if it's the home page
    if (linkHref === pageName) {
      link.classList.add('active');
    } else if (pageName === 'index.html' && linkHref === 'index.html') {
      link.classList.add('active');
    } else if (linkHref.includes('#') && pageName === linkHref.split('#')[0]) {
      // Handle links with hash/fragment
      link.classList.add('active');
    }
  });
  
  // Add smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;
      
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        // Smooth scroll to the element
        targetElement.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
        
        // Update URL without page reload
        history.pushState(null, null, targetId);
      }
    });
  });
}

// Initialize FAQ accordions
function initFaq() {
  const faqItems = document.querySelectorAll('.faq-item');
  
  if (faqItems.length > 0) {
    faqItems.forEach(item => {
      const question = item.querySelector('.faq-question');
      const answer = item.querySelector('.faq-answer');
      
      // Hide all answers initially
      if (answer) {
        answer.style.display = 'none';
      }
      
      // Add click event to questions
      if (question) {
        question.addEventListener('click', () => {
          // Toggle active class
          item.classList.toggle('active');
          
          // Toggle answer visibility
          if (answer) {
            answer.style.display = item.classList.contains('active') ? 'block' : 'none';
          }
        });
      }
    });
    
    // Open first FAQ item by default
    if (faqItems[0]) {
      faqItems[0].classList.add('active');
      const firstAnswer = faqItems[0].querySelector('.faq-answer');
      if (firstAnswer) {
        firstAnswer.style.display = 'block';
      }
    }
  }
}

// Initialize animation on scroll
function initAnimationOnScroll() {
  const animatedElements = document.querySelectorAll('.animate-on-scroll');
  
  // If IntersectionObserver is supported
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          // Unobserve element after it has been animated
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });
    
    animatedElements.forEach(element => {
      observer.observe(element);
    });
  } else {
    // Fallback for browsers that don't support IntersectionObserver
    animatedElements.forEach(element => {
      element.classList.add('in-view');
    });
  }
}
