
import React, { useEffect, useRef } from 'react';

interface CodeParticle {
  x: number;
  y: number;
  size: number;
  speed: number;
  text: string;
  opacity: number;
}

const codeSnippets = [
  '{...}', '</>', '()', '[]', '{}', 'if()', 'for()', 'const', 'let', 'import', 'export',
  'function()', '=>', '===', '&&', '||', 'return', 'this', 'class', 'new', 'async', 'await'
];

const CodeParticles: React.FC = () => {
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const particles = useRef<CodeParticle[]>([]);
  
  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    if (!ctx) return;
    
    // Set canvas to full parent width/height
    const resizeCanvas = () => {
      const parent = canvas.parentElement;
      if (parent) {
        canvas.width = parent.clientWidth;
        canvas.height = parent.clientHeight;
      }
    };
    
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();
    
    // Initialize particles
    const particleCount = Math.min(25, Math.floor(canvas.width / 50));
    particles.current = Array(particleCount).fill(null).map(() => ({
      x: Math.random() * canvas.width,
      y: canvas.height + Math.random() * 100,
      size: 10 + Math.random() * 4,
      speed: 0.5 + Math.random() * 1,
      text: codeSnippets[Math.floor(Math.random() * codeSnippets.length)],
      opacity: 0.1 + Math.random() * 0.4
    }));
    
    const animate = () => {
      if (!canvas || !ctx) return;
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      
      particles.current.forEach((particle, index) => {
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
    };
    
    const animationFrame = requestAnimationFrame(animate);
    
    return () => {
      window.removeEventListener('resize', resizeCanvas);
      cancelAnimationFrame(animationFrame);
    };
  }, []);
  
  return (
    <canvas 
      ref={canvasRef} 
      className="absolute inset-0 w-full h-full z-0 opacity-40"
    />
  );
};

export default CodeParticles;
