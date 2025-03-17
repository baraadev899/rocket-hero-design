
import { toast } from 'sonner';

const API_BASE_URL = '/admin/api';

export interface Settings {
  id: number;
  site_title: string;
  site_description: string;
  email: string;
  phone: string;
  address: string;
  facebook: string | null;
  twitter: string | null;
  instagram: string | null;
  linkedin: string | null;
  whatsapp: string | null;
  updated_at: string;
}

export interface Project {
  id: number;
  title: string;
  description: string;
  category: string;
  client: string | null;
  date: string | null;
  image: string;
  link: string | null;
  featured: number;
  created_at: string;
}

export interface Service {
  id: number;
  title: string;
  description: string;
  icon: string | null;
  image: string | null;
  order_index: number;
  created_at: string;
}

export interface TeamMember {
  id: number;
  name: string;
  position: string;
  bio: string | null;
  image: string;
  email: string | null;
  twitter: string | null;
  linkedin: string | null;
  instagram: string | null;
  order_index: number;
  created_at: string;
}

export interface FAQ {
  id: number;
  question: string;
  answer: string;
  order_index: number;
  created_at: string;
}

export interface ContactFormData {
  name: string;
  email: string;
  phone?: string;
  subject: string;
  message: string;
}

// Generic API fetch function with error handling
async function apiFetch<T>(endpoint: string, options?: RequestInit): Promise<T> {
  try {
    const response = await fetch(`${API_BASE_URL}/${endpoint}`, options);
    
    if (!response.ok) {
      throw new Error(`API error: ${response.status}`);
    }
    
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(`API Error: ${error}`);
    toast.error('حدث خطأ أثناء الاتصال بالخادم');
    throw error;
  }
}

// Settings API
export const settingsApi = {
  getSettings: async (): Promise<Settings> => {
    const response = await apiFetch<{success: boolean, data: Settings}>('settings.php');
    return response.data;
  }
};

// Projects API
export const projectsApi = {
  getAllProjects: async (): Promise<Project[]> => {
    const response = await apiFetch<{success: boolean, data: Project[]}>('projects.php');
    return response.data;
  },
  
  getFeaturedProjects: async (): Promise<Project[]> => {
    const response = await apiFetch<{success: boolean, data: Project[]}>('projects.php?featured=1');
    return response.data;
  },
  
  getProjectById: async (id: number): Promise<Project> => {
    const response = await apiFetch<{success: boolean, data: Project}>(`projects.php?id=${id}`);
    return response.data;
  }
};

// Services API
export const servicesApi = {
  getAllServices: async (): Promise<Service[]> => {
    const response = await apiFetch<{success: boolean, data: Service[]}>('services.php');
    return response.data;
  }
};

// Team API
export const teamApi = {
  getAllTeamMembers: async (): Promise<TeamMember[]> => {
    const response = await apiFetch<{success: boolean, data: TeamMember[]}>('team.php');
    return response.data;
  }
};

// FAQs API
export const faqsApi = {
  getAllFaqs: async (): Promise<FAQ[]> => {
    const response = await apiFetch<{success: boolean, data: FAQ[]}>('faqs.php');
    return response.data;
  }
};

// Contact API
export const contactApi = {
  submitContactForm: async (formData: ContactFormData): Promise<{success: boolean, message: string}> => {
    const response = await apiFetch<{success: boolean, message: string}>('contact.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    });
    
    if (response.success) {
      toast.success(response.message);
    } else {
      toast.error(response.message);
    }
    
    return response;
  }
};
