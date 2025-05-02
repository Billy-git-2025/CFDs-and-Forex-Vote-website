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
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    
    <header class="site-header">
        <div class="header-container">
            <a href="<?php echo $isBlog ? '../index.html' : 'index.html'; ?>" class="site-title">
                <img src="<?php echo $isBlog ? '../images/logo.png' : 'images/logo.png'; ?>" alt="CFDs and Forex Vote Logo">
            </a>
            
            <nav class="site-nav">
                <ul>
                    <li><a href="<?php echo $isBlog ? '../index.html' : 'index.html'; ?>" class="<?php echo $currentPage === 'index.html' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="<?php echo $isBlog ? '../compare.html' : 'compare.html'; ?>" class="<?php echo $currentPage === 'compare.html' ? 'active' : ''; ?>">Compare</a></li>
                    <li><a href="<?php echo $isBlog ? '../blog/index.html' : 'blog/index.html'; ?>" class="<?php echo $isBlog ? 'active' : ''; ?>">Blog</a></li>
                </ul>
            </nav>
            
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
    </header>

    <style>
        .site-header {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .site-title img {
            height: 40px;
            width: auto;
        }
        
        .site-nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 2rem;
        }
        
        .site-nav a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .site-nav a:hover,
        .site-nav a.active {
            color: #327fff;
        }
        
        .language-selector select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background-color: white;
        }
        
        @media (max-width: 768px) {
            .header-container {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }
            
            .site-nav ul {
                gap: 1rem;
            }
            
            .site-title img {
                height: 30px;
            }
        }
    </style>
</body>
</html> 