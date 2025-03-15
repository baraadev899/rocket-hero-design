
import React, { useEffect } from 'react';
import RocketAnimation from './RocketAnimation';
import CodeParticles from './CodeParticles';
import AnimatedButton from './AnimatedButton';
import { Sparkles, Code, BarChart } from 'lucide-react';

const HeroSection: React.FC = () => {
  // Set RTL direction when component mounts
  useEffect(() => {
    document.documentElement.setAttribute('dir', 'rtl');
    
    // Cleanup function to reset direction if needed
    return () => {
      // document.documentElement.removeAttribute('dir');
    };
  }, []);
  
  return (
    <div className="relative min-h-screen overflow-hidden bg-rocketBlack">
      {/* Background elements */}
      <div className="absolute top-0 left-0 w-full h-full">
        {/* Background gradient */}
        <div className="absolute inset-0 bg-gradient-to-b from-rocketBlack via-rocketBlack to-[#1a0a0e] z-0"></div>
        
        {/* Animated code particles */}
        <CodeParticles />
        
        {/* Abstract shapes */}
        <div className="absolute top-20 left-10 w-64 h-64 bg-rocketRed opacity-5 rounded-full blur-3xl"></div>
        <div className="absolute bottom-20 right-10 w-80 h-80 bg-rocketRed opacity-5 rounded-full blur-3xl"></div>
        
        {/* Grid pattern */}
        <div className="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiMyMTIxMjEiIGZpbGwtb3BhY2l0eT0iMC4wNCI+PHBhdGggZD0iTTM2IDM0aDR2MWgtNHYtMXptMC0yaDF2NGgtMXYtNHptLTUgMmg0djFoLTR2LTF6bTAgMmgtMXYtNGgxdjR6bS03LTNoNHYxaC00di0xem0wIDJoLTF2LTRoMXY0em0tNy0yaDF2NGgtMXYtNHptMCAyaC00di0xaDR2MXptMzYtMTJ2MTBoMTB2LTEwaC0xMHptLTUgMTVoMTB2LTEwaC0xMHYxMHptLTUgMTB2LTEwaC0xMHYxMGgxMHptLTE1LTVoMTB2LTEwaC0xMHYxMHptLTUtMTVoMTB2LTEwaC0xMHYxMHptLTE1LTVoMTB2LTEwaC0xMHYxMHptLTUgMTVoMTB2LTEwaC0xMHYxMHptLTE1LTVoMTB2LTEwaC0xMHYxMHptLTUgMTV2LTEwaC0xMHYxMGgxMHptNDAuMjMgMGgxMHYtMTBoLTEwdjEweiIvPjwvZz48L2c+PC9zdmc+')]"></div>
      </div>
      
      <div className="container relative z-10 px-6 py-16 mx-auto">
        <div className="flex flex-col-reverse lg:flex-row items-center justify-between gap-12">
          {/* Text Content */}
          <div className="w-full lg:w-1/2 text-right">
            <div className="space-y-6">
              <div className="inline-flex items-center justify-center px-3 py-1 text-xs font-medium bg-white bg-opacity-10 text-rocketRed rounded-full backdrop-blur-sm animate-fade-in">
                <Sparkles size={14} className="ml-1" />
                <span>شركة تسويق إلكتروني وبرمجة</span>
              </div>
              
              <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight animate-slide-up" style={{ animationDelay: '0.2s' }}>
                <span className="block">نحول أفكارك إلى</span>
                <span className="text-gradient">واقع رقمي مبتكر</span>
              </h1>
              
              <p className="text-lg text-white text-opacity-80 animate-slide-up" style={{ animationDelay: '0.4s' }}>
                نساعدك على إطلاق مشروعك نحو النجاح من خلال حلول تسويقية مبتكرة وتقنيات برمجية متطورة. انضم إلى قائمة عملائنا المميزين واستمتع بخدمات احترافية تحقق أهدافك.
              </p>
              
              <div className="flex flex-wrap items-center gap-5 animate-slide-up" style={{ animationDelay: '0.6s' }}>
                <AnimatedButton>
                  تواصل معنا الآن
                </AnimatedButton>
                
                <a href="#services" className="inline-flex items-center text-white hover:text-rocketRed transition-colors duration-300">
                  <span>استكشف خدماتنا</span>
                  <span className="inline-block mr-2 rotate-180">
                    <ArrowRight size={18} />
                  </span>
                </a>
              </div>
              
              <div className="flex items-center gap-8 pt-8 mt-4 border-t border-white border-opacity-10 animate-slide-up" style={{ animationDelay: '0.8s' }}>
                <div className="flex items-center">
                  <div className="p-2 bg-white bg-opacity-10 rounded-lg backdrop-blur-sm">
                    <Code size={20} className="text-rocketRed" />
                  </div>
                  <div className="mr-3">
                    <p className="text-sm text-white">تطوير المواقع</p>
                  </div>
                </div>
                
                <div className="flex items-center">
                  <div className="p-2 bg-white bg-opacity-10 rounded-lg backdrop-blur-sm">
                    <BarChart size={20} className="text-rocketRed" />
                  </div>
                  <div className="mr-3">
                    <p className="text-sm text-white">التسويق الرقمي</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          {/* Rocket Animation */}
          <div className="w-full lg:w-1/2 flex justify-center">
            <div className="relative">
              <div className="absolute inset-0 bg-rocketRed opacity-5 blur-3xl rounded-full"></div>
              
              {/* Glassmorphism card */}
              <div className="glass-effect relative rounded-2xl w-72 h-72 sm:w-80 sm:h-80 md:w-96 md:h-96 flex items-center justify-center animate-scale-up">
                <RocketAnimation />
                
                {/* Decorative elements */}
                <div className="absolute top-4 left-4 w-3 h-3 bg-rocketRed rounded-full animate-pulse-slow"></div>
                <div className="absolute bottom-4 right-4 w-3 h-3 bg-rocketRed rounded-full animate-pulse-slow" style={{animationDelay: '1s'}}></div>
                <div className="absolute top-1/2 right-4 w-2 h-2 bg-white rounded-full animate-pulse-slow" style={{animationDelay: '1.5s'}}></div>
                <div className="absolute bottom-1/3 left-6 w-2 h-2 bg-white rounded-full animate-pulse-slow" style={{animationDelay: '0.8s'}}></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      {/* Bottom decorations */}
      <div className="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-rocketRed to-transparent opacity-30"></div>
    </div>
  );
};

export default HeroSection;
