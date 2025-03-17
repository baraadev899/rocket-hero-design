
import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { 
  Facebook, 
  Twitter, 
  Instagram, 
  Linkedin, 
  Mail, 
  Phone, 
  MapPin 
} from 'lucide-react';
import { settingsApi, Settings } from '@/services/api';

const Footer = () => {
  const [settings, setSettings] = useState<Settings | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const currentYear = new Date().getFullYear();

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

  return (
    <footer className="bg-rocketBlack text-white border-t border-white/10">
      <div className="container-custom py-12">
        {/* القسم العلوي من الفوتر */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {/* معلومات الشركة */}
          <div className="space-y-4">
            <div className="flex items-center gap-2">
              <span className="text-white text-2xl font-bold">
                {isLoading ? 'روكيت' : settings?.site_title || 'روكيت'}
              </span>
              <span className="text-3xl">🚀</span>
            </div>
            <p className="text-gray-400 max-w-xs">
              {isLoading ? 'جاري التحميل...' : settings?.site_description || 'وكالة متخصصة في تصميم وبرمجة المواقع والتطبيقات وتطوير الحلول التقنية المتكاملة.'}
            </p>
            
            {/* وسائل التواصل الاجتماعي */}
            <div className="flex space-x-4 rtl:space-x-reverse">
              {settings?.facebook && (
                <a 
                  href={settings.facebook} 
                  target="_blank" 
                  rel="noopener noreferrer" 
                  className="text-gray-400 hover:text-rocketRed transition-colors"
                >
                  <Facebook size={20} />
                </a>
              )}
              {settings?.twitter && (
                <a 
                  href={settings.twitter} 
                  target="_blank" 
                  rel="noopener noreferrer" 
                  className="text-gray-400 hover:text-rocketRed transition-colors"
                >
                  <Twitter size={20} />
                </a>
              )}
              {settings?.instagram && (
                <a 
                  href={settings.instagram} 
                  target="_blank" 
                  rel="noopener noreferrer" 
                  className="text-gray-400 hover:text-rocketRed transition-colors"
                >
                  <Instagram size={20} />
                </a>
              )}
              {settings?.linkedin && (
                <a 
                  href={settings.linkedin} 
                  target="_blank" 
                  rel="noopener noreferrer" 
                  className="text-gray-400 hover:text-rocketRed transition-colors"
                >
                  <Linkedin size={20} />
                </a>
              )}
            </div>
          </div>

          {/* روابط سريعة */}
          <div>
            <h3 className="text-xl font-bold mb-4">روابط سريعة</h3>
            <ul className="space-y-2">
              <li>
                <Link to="/" className="text-gray-400 hover:text-rocketRed transition-colors">
                  الرئيسية
                </Link>
              </li>
              <li>
                <Link to="/services" className="text-gray-400 hover:text-rocketRed transition-colors">
                  خدماتنا
                </Link>
              </li>
              <li>
                <Link to="/portfolio" className="text-gray-400 hover:text-rocketRed transition-colors">
                  أعمالنا
                </Link>
              </li>
              <li>
                <Link to="/team" className="text-gray-400 hover:text-rocketRed transition-colors">
                  فريقنا
                </Link>
              </li>
              <li>
                <Link to="/contact" className="text-gray-400 hover:text-rocketRed transition-colors">
                  اتصل بنا
                </Link>
              </li>
            </ul>
          </div>

          {/* خدماتنا */}
          <div>
            <h3 className="text-xl font-bold mb-4">خدماتنا</h3>
            <ul className="space-y-2">
              <li>
                <Link to="/services#web-design" className="text-gray-400 hover:text-rocketRed transition-colors">
                  تصميم المواقع
                </Link>
              </li>
              <li>
                <Link to="/services#web-development" className="text-gray-400 hover:text-rocketRed transition-colors">
                  برمجة المواقع
                </Link>
              </li>
              <li>
                <Link to="/services#mobile-apps" className="text-gray-400 hover:text-rocketRed transition-colors">
                  تطبيقات الجوال
                </Link>
              </li>
              <li>
                <Link to="/services#ui-ux" className="text-gray-400 hover:text-rocketRed transition-colors">
                  تصميم واجهات المستخدم
                </Link>
              </li>
              <li>
                <Link to="/services#digital-marketing" className="text-gray-400 hover:text-rocketRed transition-colors">
                  التسويق الرقمي
                </Link>
              </li>
            </ul>
          </div>

          {/* معلومات الاتصال */}
          <div>
            <h3 className="text-xl font-bold mb-4">اتصل بنا</h3>
            <ul className="space-y-4">
              {settings?.address && (
                <li className="flex items-start gap-3">
                  <MapPin className="text-rocketRed mt-1 shrink-0" size={18} />
                  <span className="text-gray-400">{settings.address}</span>
                </li>
              )}
              {settings?.email && (
                <li className="flex items-center gap-3">
                  <Mail className="text-rocketRed shrink-0" size={18} />
                  <a 
                    href={`mailto:${settings.email}`} 
                    className="text-gray-400 hover:text-rocketRed transition-colors"
                  >
                    {settings.email}
                  </a>
                </li>
              )}
              {settings?.phone && (
                <li className="flex items-center gap-3">
                  <Phone className="text-rocketRed shrink-0" size={18} />
                  <a 
                    href={`tel:${settings.phone}`} 
                    className="text-gray-400 hover:text-rocketRed transition-colors"
                  >
                    {settings.phone}
                  </a>
                </li>
              )}
            </ul>
          </div>
        </div>

        {/* القسم السفلي من الفوتر */}
        <div className="border-t border-white/10 mt-8 pt-8 text-gray-400 text-sm">
          <div className="flex flex-col md:flex-row justify-between items-center gap-4">
            <p>
              &copy; {currentYear} {settings?.site_title || 'روكيت للتصميم والبرمجة'}. جميع الحقوق محفوظة.
            </p>
            <div className="flex space-x-4 rtl:space-x-reverse">
              <Link to="/privacy-policy" className="hover:text-rocketRed transition-colors">
                سياسة الخصوصية
              </Link>
              <Link to="/terms-of-service" className="hover:text-rocketRed transition-colors">
                شروط الاستخدام
              </Link>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
