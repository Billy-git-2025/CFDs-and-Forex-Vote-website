// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Website loaded successfully!');
    
    // Example function to toggle a class on click
    function initializeClickEvents() {
        const navLinks = document.querySelectorAll('nav a');
        
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove 'active' class from all links
                navLinks.forEach(item => item.classList.remove('active'));
                
                // Add 'active' class to clicked link
                this.classList.add('active');
                
                console.log('Navigated to:', this.textContent);
                
                // Here you would typically handle actual navigation or content loading
            });
        });
    }
    
    // Initialize events
    initializeClickEvents();
}); 