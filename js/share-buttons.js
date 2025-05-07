document.addEventListener('DOMContentLoaded', function() {
    const currentUrl = encodeURIComponent(window.location.href);
    const pageTitle = encodeURIComponent(document.title);
    
    // Update WhatsApp share link
    const whatsappLinks = document.querySelectorAll('.social-share .whatsapp');
    whatsappLinks.forEach(link => {
        const currentText = link.getAttribute('href').split('?text=')[1];
        link.href = `https://wa.me/?text=${currentText}${currentUrl}`;
    });
    
    // Update Twitter share link
    const twitterLinks = document.querySelectorAll('.social-share .twitter');
    twitterLinks.forEach(link => {
        const currentText = link.getAttribute('href').split('?text=')[1];
        link.href = `https://twitter.com/intent/tweet?text=${currentText}${currentUrl}`;
    });
    
    // Update Facebook share link
    const facebookLinks = document.querySelectorAll('.social-share .facebook');
    facebookLinks.forEach(link => {
        link.href = `https://www.facebook.com/sharer/sharer.php?u=${currentUrl}`;
    });
    
    // Update LinkedIn share link
    const linkedinLinks = document.querySelectorAll('.social-share .linkedin');
    linkedinLinks.forEach(link => {
        link.href = `https://www.linkedin.com/sharing/share-offsite/?url=${currentUrl}`;
    });
}); 