
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
}

// Add script tag for Lovable
if (!document.querySelector('script[src="https://cdn.gpteng.co/gptengineer.js"]')) {
  const lovableScript = document.createElement('script');
  lovableScript.src = 'https://cdn.gpteng.co/gptengineer.js';
  lovableScript.type = 'module';
  document.head.appendChild(lovableScript);
}
