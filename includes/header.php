<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $isBlog ? '../images/favicon.ico' : 'images/favicon.ico'; ?>" type="image/x-icon">
    <!-- Language Alternates -->
    <link rel="alternate" hreflang="en" href="https://cfdsandforexvote.com/" />
    <link rel="alternate" hreflang="es" href="https://cfdsandforexvote.com/es/" />
    <link rel="alternate" hreflang="fr" href="https://cfdsandforexvote.com/fr/" />
    <link rel="alternate" hreflang="de" href="https://cfdsandforexvote.com/de/" />
    <link rel="alternate" hreflang="it" href="https://cfdsandforexvote.com/it/" />
    <link rel="alternate" hreflang="zh-Hans" href="https://cfdsandforexvote.com/zh/" />
    <link rel="alternate" hreflang="zh-Hant" href="https://cfdsandforexvote.com/zh-tw/" />
    <link rel="alternate" hreflang="ar" href="https://cfdsandforexvote.com/ar/" />
    <link rel="alternate" hreflang="pt" href="https://cfdsandforexvote.com/pt/" />
    <link rel="alternate" hreflang="nl" href="https://cfdsandforexvote.com/nl/" />
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-XXXXXXX');</script>
    <!-- End Google Tag Manager -->
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-XXXXXXX-X"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-XXXXXXX-X');
    </script>
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $metaDescription; ?>">
    <!-- Base Styles -->
    <link rel="stylesheet" href="<?php echo $isBlog ? '../css/styles.css' : 'css/styles.css'; ?>">
    <!-- Page Specific Styles -->
    <?php if ($currentPage === 'index.html'): ?>
        <link rel="stylesheet" href="css/homepage-styles.css">
    <?php endif; ?>
    <?php if ($currentPage === 'compare.html'): ?>
        <link rel="stylesheet" href="css/compare-styles.css">
    <?php endif; ?>
    <?php if ($isBlog): ?>
        <link rel="stylesheet" href="../css/blog-styles.css">
    <?php endif; ?>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Discord Link -->
    <div class="discord-link">
        <a href="https://discord.gg/your-discord-link" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-discord"></i> Join our Discord
        </a>
    </div>
    <style>
        .discord-link {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .discord-link a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: #7289DA;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        
        .discord-link a:hover {
            background-color: #5f73bc;
        }
        
        @media (max-width: 768px) {
            .discord-link {
                bottom: 15px;
                right: 15px;
            }
            
            .discord-link a {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    
    <header>
        <nav>
            <div class="nav-container">
                <a href="<?php echo $isBlog ? '../index.html' : 'index.html'; ?>" class="logo">
                    <img src="<?php echo $isBlog ? '../images/logo.png' : 'images/logo.png'; ?>" alt="CFDs and Forex Vote Logo">
                </a>
                
                <div class="nav-links">
                    <a href="<?php echo $isBlog ? '../index.html' : 'index.html'; ?>" class="<?php echo $currentPage === 'index.html' ? 'active' : ''; ?>">Home</a>
                    <a href="<?php echo $isBlog ? '../compare.html' : 'compare.html'; ?>" class="<?php echo $currentPage === 'compare.html' ? 'active' : ''; ?>">Compare</a>
                    <a href="<?php echo $isBlog ? '../blog/index.html' : 'blog/index.html'; ?>" class="<?php echo $isBlog ? 'active' : ''; ?>">Blog</a>
                </div>
                
                <div class="language-selector">
                    <select onchange="window.location.href=this.value">
                        <option value="https://cfdsandforexvote.com/" <?php echo $currentLang === 'en' ? 'selected' : ''; ?>>English</option>
                        <option value="https://cfdsandforexvote.com/es/" <?php echo $currentLang === 'es' ? 'selected' : ''; ?>>Español</option>
                        <option value="https://cfdsandforexvote.com/fr/" <?php echo $currentLang === 'fr' ? 'selected' : ''; ?>>Français</option>
                        <option value="https://cfdsandforexvote.com/de/" <?php echo $currentLang === 'de' ? 'selected' : ''; ?>>Deutsch</option>
                        <option value="https://cfdsandforexvote.com/it/" <?php echo $currentLang === 'it' ? 'selected' : ''; ?>>Italiano</option>
                        <option value="https://cfdsandforexvote.com/zh/" <?php echo $currentLang === 'zh' ? 'selected' : ''; ?>>中文 (简体)</option>
                        <option value="https://cfdsandforexvote.com/zh-tw/" <?php echo $currentLang === 'zh-tw' ? 'selected' : ''; ?>>中文 (繁體)</option>
                        <option value="https://cfdsandforexvote.com/ar/" <?php echo $currentLang === 'ar' ? 'selected' : ''; ?>>العربية</option>
                        <option value="https://cfdsandforexvote.com/pt/" <?php echo $currentLang === 'pt' ? 'selected' : ''; ?>>Português</option>
                        <option value="https://cfdsandforexvote.com/nl/" <?php echo $currentLang === 'nl' ? 'selected' : ''; ?>>Nederlands</option>
                    </select>
                </div>
            </div>
        </nav>
    </header>
</body>
</html> 