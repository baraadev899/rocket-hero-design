
// Toggle sidebar on mobile
document.addEventListener('DOMContentLoaded', function() {
    // Create toggle button for mobile
    const createToggleButton = () => {
        if (window.innerWidth <= 576) {
            // Check if button already exists
            if (!document.querySelector('.toggle-sidebar')) {
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'toggle-sidebar';
                toggleBtn.innerHTML = '☰';
                document.body.appendChild(toggleBtn);
                
                // Add event listener
                toggleBtn.addEventListener('click', function() {
                    const sidebar = document.querySelector('.sidebar');
                    sidebar.classList.toggle('show');
                });
            }
        } else {
            // Remove button if window is resized
            const existingBtn = document.querySelector('.toggle-sidebar');
            if (existingBtn) {
                existingBtn.remove();
            }
        }
    };
    
    // Initialize on load
    createToggleButton();
    
    // Update on resize
    window.addEventListener('resize', createToggleButton);
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 576) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('.toggle-sidebar');
            
            if (sidebar && sidebar.classList.contains('show') && 
                !sidebar.contains(event.target) && 
                toggleBtn && !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
});
