
// Wait for DOM to be loaded
document.addEventListener('DOMContentLoaded', function() {
  // Initialize the navbar
  initNavbar();
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
  
  // Set active link based on current page
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-link').forEach(link => {
    const linkHref = link.getAttribute('href');
    if (linkHref === currentPage) {
      link.classList.add('active');
    } else if (currentPage === 'index.html' && linkHref === 'index.html') {
      link.classList.add('active');
    }
  });
}

// Add Lovable script tag initialization
function addLovableScript() {
  const script = document.createElement('script');
  script.src = 'https://cdn.gpteng.co/gptengineer.js';
  script.type = 'module';
  document.head.appendChild(script);
}

// Initialize Lovable script
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', addLovableScript);
} else {
  addLovableScript();
}
