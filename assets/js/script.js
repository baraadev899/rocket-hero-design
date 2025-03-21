
// انتظر حتى يتم تحميل DOM بالكامل
document.addEventListener('DOMContentLoaded', function() {
  // تهيئة عناصر الموقع
  initMobileMenu();
  initThemeToggle();
  
  // تهيئة الرسوم المتحركة
  initCodeParticles();
  initRocketAnimation();
  
  // تهيئة عناصر صفحات محددة إذا كانت موجودة
  // قسم الأسئلة الشائعة في صفحة الخدمات
  if (document.querySelector('.faq-item')) {
    initFaqToggles();
  }
  
  // تصفية المشاريع في صفحة الأعمال
  if (document.querySelector('.portfolio-filters')) {
    initPortfolioFilters();
  }
  
  // نموذج الاتصال في صفحة اتصل بنا
  if (document.querySelector('.contact-form')) {
    initContactForm();
  }
  
  // تأثيرات فريق العمل في صفحة الفريق
  if (document.querySelector('.team-member')) {
    initTeamMemberEffects();
  }
  
  // قسم التعليقات إذا كان موجوداً
  if (document.querySelector('.comments-section')) {
    initCommentsSection();
  }
  
  // تنقل المشاريع إذا كان موجوداً
  if (document.querySelector('.portfolio-navigation')) {
    initPortfolioNavigation();
  }
  
  // تهيئة تحريك العناصر عند التمرير
  initScrollAnimations();
});

// قائمة الجوال
function initMobileMenu() {
  const mobileMenuToggle = document.getElementById('mobileMenuToggle');
  const mainNav = document.querySelector('.main-nav');
  
  if (mobileMenuToggle && mainNav) {
    mobileMenuToggle.addEventListener('click', function() {
      this.classList.toggle('active');
      mainNav.classList.toggle('active');
    });
    
    // إغلاق القائمة عند النقر على أي رابط
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
      link.addEventListener('click', function() {
        mobileMenuToggle.classList.remove('active');
        mainNav.classList.remove('active');
      });
    });
  }
}

// تبديل الوضع المظلم/الفاتح
function initThemeToggle() {
  const themeToggle = document.getElementById('themeToggle');
  
  if (themeToggle) {
    // تحقق من الإعدادات المحفوظة
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.body.classList.toggle('dark-mode', savedTheme === 'dark');
    updateThemeIcon(themeToggle, savedTheme === 'dark');
    
    themeToggle.addEventListener('click', function() {
      const isDarkMode = document.body.classList.toggle('dark-mode');
      localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
      updateThemeIcon(themeToggle, isDarkMode);
    });
  }
}

// تحديث أيقونة الوضع
function updateThemeIcon(themeToggle, isDarkMode) {
  const icon = themeToggle.querySelector('i');
  if (icon) {
    icon.className = isDarkMode ? 'fas fa-sun' : 'fas fa-moon';
  }
}

// رسوم جزيئات الكود المتحركة
function initCodeParticles() {
  const canvas = document.getElementById('codeParticles');
  if (!canvas) return;
  
  const ctx = canvas.getContext('2d');
  
  // ضبط حجم الكانفاس ليناسب حجم العنصر الأب
  function resizeCanvas() {
    const parent = canvas.parentElement;
    canvas.width = parent.clientWidth;
    canvas.height = parent.clientHeight;
  }
  
  window.addEventListener('resize', resizeCanvas);
  resizeCanvas();
  
  // مقتطفات الكود للجزيئات
  const codeSnippets = [
    '{...}', '</>', '()', '[]', '{}', 'if()', 'for()', 'const', 'let', 'import', 'export',
    'function()', '=>', '===', '&&', '||', 'return', 'this', 'class', 'new', 'async', 'await'
  ];
  
  // تهيئة الجزيئات
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
  
  // حلقة الرسم المتحركة
  function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    particles.forEach(particle => {
      // تحديث موضع الجزيئات
      particle.y -= particle.speed;
      
      // إعادة تعيين الجزيئة إذا خرجت خارج الشاشة
      if (particle.y < -20) {
        particle.y = canvas.height + 10;
        particle.x = Math.random() * canvas.width;
        particle.text = codeSnippets[Math.floor(Math.random() * codeSnippets.length)];
      }
      
      // رسم الجزيئة
      ctx.font = `${particle.size}px 'Courier New', monospace`;
      ctx.fillStyle = `rgba(255, 255, 255, ${particle.opacity})`;
      ctx.textAlign = 'center';
      ctx.fillText(particle.text, particle.x, particle.y);
    });
    
    requestAnimationFrame(animate);
  }
  
  animate();
}

// تحريك الصاروخ
function initRocketAnimation() {
  const rocket = document.getElementById('rocket');
  if (!rocket) return;
  
  // متابعة مؤشر الفأرة
  document.addEventListener('mousemove', e => {
    const rocketRect = rocket.getBoundingClientRect();
    const centerX = rocketRect.left + rocketRect.width / 2;
    const centerY = rocketRect.top + rocketRect.height / 2;
    
    const deltaX = (e.clientX - centerX) / 30;
    const deltaY = (e.clientY - centerY) / 30;
    
    rocket.style.transform = `translate(${deltaX}px, ${deltaY}px) rotate(${deltaX}deg)`;
  });
}

// تبديل الأسئلة الشائعة
function initFaqToggles() {
  const faqItems = document.querySelectorAll('.faq-item');
  
  faqItems.forEach(item => {
    const question = item.querySelector('.faq-question');
    
    question.addEventListener('click', () => {
      item.classList.toggle('active');
      
      // إغلاق العناصر الأخرى
      faqItems.forEach(otherItem => {
        if (otherItem !== item) {
          otherItem.classList.remove('active');
        }
      });
    });
  });
}

// تصفية المشاريع
function initPortfolioFilters() {
  const filterButtons = document.querySelectorAll('.filter-button');
  const portfolioItems = document.querySelectorAll('.portfolio-item');
  
  filterButtons.forEach(button => {
    button.addEventListener('click', () => {
      // إعادة تعيين حالة النشاط
      filterButtons.forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');
      
      const filterValue = button.dataset.filter;
      
      portfolioItems.forEach(item => {
        if (filterValue === 'all' || item.dataset.category === filterValue) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });
}

// نموذج الاتصال مع تحسين التحقق والتغذية الراجعة
function initContactForm() {
  const contactForm = document.querySelector('.contact-form');
  
  if (!contactForm) return;
  
  contactForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // الحصول على بيانات النموذج
    const formData = new FormData(contactForm);
    let isValid = true;
    let firstInvalidField = null;
    
    // التحقق من الحقول المطلوبة
    contactForm.querySelectorAll('[required]').forEach(field => {
      if (!field.value.trim()) {
        isValid = false;
        field.classList.add('invalid');
        
        // تخزين أول حقل غير صالح للتركيز عليه
        if (!firstInvalidField) {
          firstInvalidField = field;
        }
      } else {
        field.classList.remove('invalid');
      }
      
      // التحقق من صحة البريد الإلكتروني
      if (field.type === 'email' && field.value.trim()) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(field.value)) {
          isValid = false;
          field.classList.add('invalid');
          if (!firstInvalidField) {
            firstInvalidField = field;
          }
        }
      }
    });
    
    if (!isValid) {
      // التركيز على أول حقل غير صالح
      if (firstInvalidField) {
        firstInvalidField.focus();
      }
      
      // عرض رسالة التحقق
      showMessage(contactForm, 'يرجى ملء جميع الحقول المطلوبة بشكل صحيح', 'error');
      return;
    }
    
    // إظهار مؤشر التحميل
    const submitButton = contactForm.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.innerHTML = '<span class="loading-spinner"></span> جاري الإرسال...';
    submitButton.disabled = true;
    
    // إرسال البيانات إلى السيرفر
    fetch('./api/contact.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // عرض رسالة النجاح
        contactForm.innerHTML = `
          <div class="form-success">
            <div class="success-icon">✓</div>
            <h3>شكراً لتواصلك معنا!</h3>
            <p>تم استلام رسالتك بنجاح وسنرد عليك في أقرب وقت ممكن.</p>
          </div>
        `;
      } else {
        // إعادة زر الإرسال إلى حالته الأصلية
        submitButton.innerHTML = originalButtonText;
        submitButton.disabled = false;
        
        // عرض رسالة الخطأ
        showMessage(contactForm, data.message || 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.', 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      
      // إعادة زر الإرسال إلى حالته الأصلية
      submitButton.innerHTML = originalButtonText;
      submitButton.disabled = false;
      
      // عرض رسالة الخطأ
      showMessage(contactForm, 'حدث خطأ أثناء الاتصال بالخادم. يرجى التحقق من اتصالك بالإنترنت والمحاولة مرة أخرى.', 'error');
    });
  });
}

// عرض رسالة على النموذج
function showMessage(form, text, type = 'success') {
  // إزالة أي رسائل موجودة
  const existingMessage = form.querySelector('.validation-message');
  if (existingMessage) {
    existingMessage.remove();
  }
  
  // إضافة الرسالة الجديدة
  const validationMessage = document.createElement('div');
  validationMessage.className = `validation-message ${type}`;
  validationMessage.textContent = text;
  
  form.insertBefore(validationMessage, form.firstChild);
  
  // إزالة الرسالة بعد 4 ثوانٍ
  setTimeout(() => {
    validationMessage.classList.add('fade-out');
    setTimeout(() => {
      validationMessage.remove();
    }, 300);
  }, 4000);
}

// تأثيرات أعضاء الفريق
function initTeamMemberEffects() {
  const teamMembers = document.querySelectorAll('.team-member');
  
  teamMembers.forEach(member => {
    member.addEventListener('mouseenter', function() {
      this.classList.add('hovered');
    });
    
    member.addEventListener('mouseleave', function() {
      this.classList.remove('hovered');
    });
  });
}

// تحريك العناصر عند التمرير
function initScrollAnimations() {
  const animatedElements = document.querySelectorAll('.animate-on-scroll');
  
  function checkScroll() {
    animatedElements.forEach(element => {
      const elementTop = element.getBoundingClientRect().top;
      const windowHeight = window.innerHeight;
      
      if (elementTop < windowHeight * 0.8) {
        element.classList.add('visible');
      }
    });
  }
  
  // التحقق من العناصر عند التحميل الأولي
  checkScroll();
  
  // التحقق من العناصر عند التمرير
  window.addEventListener('scroll', checkScroll);
}

// إدارة قسم التعليقات
function initCommentsSection() {
  const commentsForm = document.querySelector('.comments-form');
  const commentsList = document.querySelector('.comments-list');
  
  if (commentsForm && commentsList) {
    commentsForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // الحصول على بيانات النموذج
      const name = commentsForm.querySelector('#comment-name').value;
      const email = commentsForm.querySelector('#comment-email').value;
      const content = commentsForm.querySelector('#comment-content').value;
      
      if (!name || !email || !content) {
        showMessage(commentsForm, 'يرجى ملء جميع الحقول المطلوبة', 'error');
        return;
      }
      
      // إنشاء تعليق جديد
      const newComment = document.createElement('div');
      newComment.className = 'comment-item';
      
      // الحصول على التاريخ الحالي
      const now = new Date();
      const dateString = `${now.getDate()}/${now.getMonth() + 1}/${now.getFullYear()}`;
      
      newComment.innerHTML = `
        <div class="comment-author">
          <div class="author-image">
            <div class="default-avatar">${name.charAt(0)}</div>
          </div>
          <div class="author-info">
            <h4 class="author-name">${name}</h4>
            <p class="comment-date">${dateString}</p>
          </div>
        </div>
        <div class="comment-content">
          <p>${content}</p>
        </div>
      `;
      
      // إضافة التعليق إلى القائمة
      commentsList.appendChild(newComment);
      
      // إعادة تعيين النموذج
      commentsForm.reset();
      
      // عرض رسالة النجاح
      showMessage(commentsForm, 'تم إضافة تعليقك بنجاح', 'success');
    });
  }
}

// تنقل المشاريع
function initPortfolioNavigation() {
  const portfolioNavBtns = document.querySelectorAll('.portfolio-nav-btn');
  
  portfolioNavBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      
      const targetId = this.getAttribute('data-target');
      if (!targetId) return;
      
      // إخفاء جميع المشاريع
      document.querySelectorAll('.portfolio-project').forEach(project => {
        project.style.display = 'none';
      });
      
      // إظهار المشروع المستهدف
      const targetProject = document.getElementById(targetId);
      if (targetProject) {
        targetProject.style.display = 'block';
        
        // التمرير إلى المشروع
        targetProject.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
      
      // تحديث الزر النشط
      portfolioNavBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
    });
  });
}
