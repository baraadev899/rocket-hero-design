
import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { Menu, X } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { settingsApi, Settings } from '@/services/api';

const Navbar = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const [settings, setSettings] = useState<Settings | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  // التحقق من التمرير
  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 50);
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  // جلب إعدادات الموقع
  useEffect(() => {
    const fetchSettings = async () => {
      try {
        const data = await settingsApi.getSettings();
        setSettings(data);
      } catch (error) {
        console.error('خطأ في جلب إعدادات الموقع:', error);
      } finally {
        setIsLoading(false);
      }
    };

    fetchSettings();
  }, []);

  const toggleMenu = () => {
    setIsMenuOpen(!isMenuOpen);
  };

  return (
    <header 
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        isScrolled ? 'bg-black/80 backdrop-blur-md shadow-md py-2' : 'bg-transparent py-4'
      }`}
    >
      <div className="container-custom">
        <div className="flex justify-between items-center">
          {/* الشعار */}
          <Link to="/" className="flex items-center gap-2">
            <span className="text-white text-2xl font-bold">
              {isLoading ? 'روكيت' : settings?.site_title || 'روكيت'}
            </span>
            <span className="text-3xl">🚀</span>
          </Link>

          {/* القائمة الرئيسية - سطح المكتب */}
          <nav className="hidden md:flex items-center space-x-1 rtl:space-x-reverse">
            <Link to="/" className="px-4 py-2 text-white hover:text-rocketRed transition-colors">
              الرئيسية
            </Link>
            <Link to="/services" className="px-4 py-2 text-white hover:text-rocketRed transition-colors">
              خدماتنا
            </Link>
            <Link to="/portfolio" className="px-4 py-2 text-white hover:text-rocketRed transition-colors">
              أعمالنا
            </Link>
            <Link to="/team" className="px-4 py-2 text-white hover:text-rocketRed transition-colors">
              فريقنا
            </Link>
            <Link to="/contact" className="px-4 py-2 text-white hover:text-rocketRed transition-colors">
              اتصل بنا
            </Link>
          </nav>

          {/* زر الاتصال - سطح المكتب */}
          <div className="hidden md:block">
            <Button variant="default" className="bg-rocketRed hover:bg-rocketDarkRed">
              احصل على عرض سعر
            </Button>
          </div>

          {/* زر القائمة المتنقلة - الجوال */}
          <button 
            className="md:hidden text-white"
            onClick={toggleMenu}
            aria-label={isMenuOpen ? 'إغلاق القائمة' : 'فتح القائمة'}
          >
            {isMenuOpen ? <X size={24} /> : <Menu size={24} />}
          </button>
        </div>

        {/* القائمة المتنقلة - الجوال */}
        {isMenuOpen && (
          <div className="md:hidden mt-4 bg-black/95 rounded-lg overflow-hidden p-4">
            <nav className="flex flex-col space-y-3">
              <Link 
                to="/" 
                className="px-4 py-2 text-white hover:bg-rocketRed/10 rounded-md transition-colors"
                onClick={() => setIsMenuOpen(false)}
              >
                الرئيسية
              </Link>
              <Link 
                to="/services" 
                className="px-4 py-2 text-white hover:bg-rocketRed/10 rounded-md transition-colors"
                onClick={() => setIsMenuOpen(false)}
              >
                خدماتنا
              </Link>
              <Link 
                to="/portfolio" 
                className="px-4 py-2 text-white hover:bg-rocketRed/10 rounded-md transition-colors"
                onClick={() => setIsMenuOpen(false)}
              >
                أعمالنا
              </Link>
              <Link 
                to="/team" 
                className="px-4 py-2 text-white hover:bg-rocketRed/10 rounded-md transition-colors"
                onClick={() => setIsMenuOpen(false)}
              >
                فريقنا
              </Link>
              <Link 
                to="/contact" 
                className="px-4 py-2 text-white hover:bg-rocketRed/10 rounded-md transition-colors"
                onClick={() => setIsMenuOpen(false)}
              >
                اتصل بنا
              </Link>
              <Button 
                variant="default" 
                className="bg-rocketRed hover:bg-rocketDarkRed w-full mt-2"
                onClick={() => setIsMenuOpen(false)}
              >
                احصل على عرض سعر
              </Button>
            </nav>
          </div>
        )}
      </div>
    </header>
  );
};

export default Navbar;
