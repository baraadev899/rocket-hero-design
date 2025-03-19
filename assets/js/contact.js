
// Contact Form Handling
const contactForm = document.getElementById('contactForm');
const formMessage = document.getElementById('formMessage');
const contactDetails = document.getElementById('contactDetails');

// Update contact details from settings
async function updateContactDetails() {
    try {
        const response = await fetch('admin/api/settings.php');
        const data = await response.json();
        
        if (data.success && data.data) {
            const settings = data.data;
            
            if (contactDetails) {
                // Update address
                const addressElement = contactDetails.querySelector('.contact-item:nth-child(1) p');
                if (addressElement && settings.address) {
                    addressElement.textContent = settings.address;
                }
                
                // Update phone
                const phoneElement = contactDetails.querySelector('.contact-item:nth-child(2) p');
                if (phoneElement && settings.phone) {
                    phoneElement.textContent = settings.phone;
                }
                
                // Update email
                const emailElement = contactDetails.querySelector('.contact-item:nth-child(3) p');
                if (emailElement && settings.email) {
                    emailElement.textContent = settings.email;
                }
            }
        }
    } catch (error) {
        console.error('Error fetching contact details:', error);
    }
}

// Handle form submission
if (contactForm) {
    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(contactForm);
        
        try {
            // Show loading state
            const submitButton = contactForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;
            submitButton.textContent = 'جاري الإرسال...';
            submitButton.disabled = true;
            
            // Send form data to server
            const response = await fetch('admin/api/messages.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            // Reset button state
            submitButton.textContent = originalButtonText;
            submitButton.disabled = false;
            
            // Handle response
            if (result.success) {
                // Show success message
                formMessage.className = 'form-message success';
                formMessage.textContent = 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.';
                
                // Reset form
                contactForm.reset();
            } else {
                // Show error message
                formMessage.className = 'form-message error';
                formMessage.textContent = result.message || 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.';
            }
            
            // Hide message after 5 seconds
            setTimeout(() => {
                formMessage.style.display = 'none';
            }, 5000);
        } catch (error) {
            console.error('Error submitting form:', error);
            
            // Show error message
            formMessage.className = 'form-message error';
            formMessage.textContent = 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.';
            
            // Reset button state
            submitButton.textContent = originalButtonText;
            submitButton.disabled = false;
        }
    });
}

// Initialize page
document.addEventListener('DOMContentLoaded', () => {
    updateContactDetails();
});
