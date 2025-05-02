<footer>
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>CFDs and Forex Vote is dedicated to helping traders find the best brokers and make informed trading decisions.</p>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="<?php echo $isBlog ? '../index.html' : 'index.html'; ?>">Home</a></li>
                    <li><a href="<?php echo $isBlog ? '../compare.html' : 'compare.html'; ?>">Compare</a></li>
                    <li><a href="<?php echo $isBlog ? '../blog/index.html' : 'blog/index.html'; ?>">Blog</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Connect With Us</h3>
                <div class="social-links">
                    <a href="https://discord.gg/your-discord-link" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-discord"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> CFDs and Forex Vote. All rights reserved.</p>
            <p><a href="/sitemap.xml">Sitemap</a></p>
        </div>
    </div>
</footer>

<style>
    footer {
        background-color: #1a1a1a;
        color: #e4e4e4;
        padding: 40px 0 20px;
        margin-top: 60px;
    }
    
    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .footer-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 30px;
    }
    
    .footer-section h3 {
        color: #ffffff;
        font-size: 1.2rem;
        margin-bottom: 15px;
    }
    
    .footer-section p {
        line-height: 1.6;
        margin-bottom: 15px;
    }
    
    .footer-section ul {
        list-style: none;
        padding: 0;
    }
    
    .footer-section ul li {
        margin-bottom: 10px;
    }
    
    .footer-section a {
        color: #e4e4e4;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .footer-section a:hover {
        color: #327fff;
    }
    
    .social-links {
        display: flex;
        gap: 15px;
    }
    
    .social-links a {
        color: #e4e4e4;
        font-size: 1.5rem;
        transition: color 0.3s ease;
    }
    
    .social-links a:hover {
        color: #327fff;
    }
    
    .footer-bottom {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #333;
    }
    
    .footer-bottom p {
        margin: 5px 0;
    }
    
    @media (max-width: 768px) {
        footer {
            padding: 30px 0 15px;
        }
        
        .footer-content {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .footer-section h3 {
            font-size: 1.1rem;
        }
    }
</style>
</body>
</html> 