
import React, { useEffect, useRef } from 'react';
import { Rocket } from 'lucide-react';

const RocketAnimation: React.FC = () => {
  const rocketRef = useRef<HTMLDivElement>(null);
  
  useEffect(() => {
    const rocketElement = rocketRef.current;
    const handleMouseMove = (e: MouseEvent) => {
      if (!rocketElement) return;
      
      const rect = rocketElement.getBoundingClientRect();
      const centerX = rect.left + rect.width / 2;
      const centerY = rect.top + rect.height / 2;
      
      const deltaX = (e.clientX - centerX) / 30;
      const deltaY = (e.clientY - centerY) / 30;
      
      rocketElement.style.transform = `translate(${deltaX}px, ${deltaY}px) rotate(${deltaX}deg)`;
    };
    
    document.addEventListener('mousemove', handleMouseMove);
    
    return () => {
      document.removeEventListener('mousemove', handleMouseMove);
    };
  }, []);
  
  return (
    <div className="relative w-32 h-32 sm:w-40 sm:h-40 mx-auto">
      <div 
        ref={rocketRef}
        className="absolute inset-0 flex items-center justify-center animate-float transition-transform duration-300 ease-out"
      >
        <Rocket 
          size={96} 
          color="#E62B4A"
          className="relative z-20"
        />
        
        {/* Rocket flame animation */}
        <div className="absolute bottom-2 left-1/2 -translate-x-1/2 w-6 h-10 bg-gradient-to-t from-orange-500 via-yellow-400 to-transparent rounded-b-full animate-rocket-flame opacity-90 z-10"></div>
        
        {/* Glow effect */}
        <div className="absolute inset-0 bg-rocketRed opacity-20 blur-xl rounded-full animate-pulse-slow"></div>
      </div>
      
      {/* Orbit circles */}
      <div className="absolute inset-0 border-2 border-rocketRed border-opacity-20 rounded-full animate-rotate-slow"></div>
      <div className="absolute inset-3 border border-white border-opacity-10 rounded-full animate-rotate-slow" style={{animationDirection: 'reverse'}}></div>
    </div>
  );
};

export default RocketAnimation;
