
import React, { useEffect, useState } from 'react';
import Layout from '@/components/layout/Layout';
import HeroSection from '@/components/hero/HeroSection';
import { projectsApi, servicesApi, Project, Service } from '@/services/api';

const Index = () => {
  const [featuredProjects, setFeaturedProjects] = useState<Project[]>([]);
  const [services, setServices] = useState<Service[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        // جلب المشاريع المميزة
        const projectsData = await projectsApi.getFeaturedProjects();
        setFeaturedProjects(projectsData);
        
        // جلب الخدمات
        const servicesData = await servicesApi.getAllServices();
        setServices(servicesData);
      } catch (error) {
        console.error('خطأ في جلب البيانات:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  return (
    <Layout>
      <div className="min-h-screen bg-rocketBlack">
        <HeroSection />
        {/* يمكن إضافة المزيد من الأقسام هنا مثل الخدمات والمشاريع المميزة */}
      </div>
    </Layout>
  );
};

export default Index;
