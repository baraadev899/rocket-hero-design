
// Wait for DOM to be loaded
document.addEventListener('DOMContentLoaded', function() {
  // Initialize the code particles animation
  initCodeParticles();
  
  // Initialize the rocket animation
  initRocketAnimation();
});

// Code Particles Animation
function initCodeParticles() {
  const canvas = document.getElementById('codeParticles');
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
  
  // Button animation
  const animatedButton = document.querySelector('.animated-button');
  
  animatedButton.addEventListener('click', function() {
    alert('شكراً للتواصل معنا! سنرد عليك قريباً.');
  });
}
