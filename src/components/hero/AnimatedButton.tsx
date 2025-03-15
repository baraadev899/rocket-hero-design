
import React from 'react';
import { ArrowRight } from 'lucide-react';
import { cn } from '@/lib/utils';

interface AnimatedButtonProps {
  children: React.ReactNode;
  className?: string;
  onClick?: () => void;
  icon?: React.ReactNode;
}

const AnimatedButton: React.FC<AnimatedButtonProps> = ({ 
  children, 
  className,
  onClick,
  icon = <ArrowRight className="ml-2 rtl:mr-2 rtl:ml-0 rtl:rotate-180" size={18} />
}) => {
  return (
    <button
      onClick={onClick}
      className={cn(
        "relative overflow-hidden btn-shine group inline-flex items-center justify-center",
        "px-6 py-3 text-base font-medium rounded-md transition-all duration-300",
        "bg-rocketRed text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-rocketRed focus:ring-opacity-50",
        "transform hover:scale-105 active:scale-95",
        className
      )}
    >
      <span className="relative z-10 flex items-center">
        {children}
        <span className="inline-block transition-transform duration-300 group-hover:translate-x-1 group-hover:rtl:-translate-x-1">
          {icon}
        </span>
      </span>
      <span className="absolute inset-0 bg-gradient-to-r from-rocketRed to-red-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></span>
    </button>
  );
};

export default AnimatedButton;
