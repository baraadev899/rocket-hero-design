
// Enhanced UI effects and animations for dark mode
document.addEventListener('DOMContentLoaded', function() {
  // Add hover effects to buttons
  addButtonEffects();
  
  // Add glass morphism effect to key elements
  addGlassMorphism();
  
  // Add hover effects to cards
  addCardHoverEffects();
  
  // Initialize section transition effects
  initSectionTransitions();
  
  // Add particle background effect (if element exists)
  if (document.getElementById('particles-bg')) {
    initParticleBackground();
  }
});

// Add special hover effects to buttons
function addButtonEffects() {
  const buttons = document.querySelectorAll('.btn-primary, .btn-outline');
  
  buttons.forEach(button => {
    button.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-3px)';
      this.style.boxShadow = '0 7px 14px rgba(0, 0, 0, 0.2)';
    });
    
    button.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
      this.style.boxShadow = 'none';
    });
  });
}

// Add glass morphism effect to elements
function addGlassMorphism() {
  const glassElements = document.querySelectorAll('.service-card, .project-card, .team-card, .contact-info, .contact-form-wrapper');
  
  glassElements.forEach(element => {
    element.classList.add('glass-effect');
  });
}

// Add hover effects to cards
function addCardHoverEffects() {
  const cards = document.querySelectorAll('.service-card, .project-card, .team-card');
  
  cards.forEach(card => {
    card.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-10px)';
      this.style.boxShadow = '0 15px 30px rgba(0, 0, 0, 0.3)';
      this.style.borderColor = 'rgba(255, 255, 255, 0.1)';
      this.style.backgroundColor = 'rgba(255, 255, 255, 0.05)';
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
      this.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.2)';
      this.style.borderColor = 'rgba(255, 255, 255, 0.05)';
      this.style.backgroundColor = 'rgba(255, 255, 255, 0.03)';
    });
  });
}

// Initialize section transition effects
function initSectionTransitions() {
  const sections = document.querySelectorAll('.section');
  
  // Create an Intersection Observer
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('section-visible');
      }
    });
  }, { threshold: 0.1 });
  
  // Observe each section
  sections.forEach(section => {
    section.classList.add('section-transition');
    observer.observe(section);
  });
}

// Initialize particle background
function initParticleBackground() {
  const canvas = document.getElementById('particles-bg');
  if (!canvas) return;
  
  const ctx = canvas.getContext('2d');
  
  // Set canvas to full window width/height
  function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  }
  
  window.addEventListener('resize', resizeCanvas);
  resizeCanvas();
  
  // Create particles
  const particles = [];
  const particleCount = 100;
  
  for (let i = 0; i < particleCount; i++) {
    particles.push({
      x: Math.random() * canvas.width,
      y: Math.random() * canvas.height,
      radius: Math.random() * 2 + 1,
      speedX: Math.random() * 0.5 - 0.25,
      speedY: Math.random() * 0.5 - 0.25,
      color: `rgba(255, 255, 255, ${Math.random() * 0.3 + 0.1})`
    });
  }
  
  // Animation loop
  function animate() {
    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Update and draw particles
    particles.forEach(particle => {
      // Update position
      particle.x += particle.speedX;
      particle.y += particle.speedY;
      
      // Wrap around screen
      if (particle.x < 0) particle.x = canvas.width;
      if (particle.x > canvas.width) particle.x = 0;
      if (particle.y < 0) particle.y = canvas.height;
      if (particle.y > canvas.height) particle.y = 0;
      
      // Draw particle
      ctx.beginPath();
      ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
      ctx.fillStyle = particle.color;
      ctx.fill();
    });
    
    // Draw connections between nearby particles
    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const dx = particles[i].x - particles[j].x;
        const dy = particles[i].y - particles[j].y;
        const distance = Math.sqrt(dx * dx + dy * dy);
        
        if (distance < 100) {
          ctx.beginPath();
          ctx.strokeStyle = `rgba(255, 255, 255, ${0.1 * (1 - distance / 100)})`;
          ctx.lineWidth = 0.5;
          ctx.moveTo(particles[i].x, particles[i].y);
          ctx.lineTo(particles[j].x, particles[j].y);
          ctx.stroke();
        }
      }
    }
    
    requestAnimationFrame(animate);
  }
  
  animate();
}

// CSS class for section transitions
const style = document.createElement('style');
style.textContent = `
  .section-transition {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.8s ease, transform 0.8s ease;
  }
  
  .section-visible {
    opacity: 1;
    transform: translateY(0);
  }
  
  @keyframes float {
    0% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0); }
  }
  
  .float-animation {
    animation: float 5s ease-in-out infinite;
  }
`;
document.head.appendChild(style);
