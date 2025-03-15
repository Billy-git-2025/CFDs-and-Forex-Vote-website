<?php
require_once 'includes/config.php';
require_once 'includes/blog.php';

// Initialize database if needed
init_database();

// Get page number for pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1
$posts_per_page = 12; // Show more posts per page
$offset = ($page - 1) * $posts_per_page;

// Get posts for current page
$posts = get_posts($posts_per_page, $offset);

// If no posts from the database, use our static blog posts
if (empty($posts)) {
    // Create static blog posts as fallback
    $posts = [
        [
            'id' => 1,
            'title' => 'Market Analysis: Weekly Review',
            'slug' => 'market-analysis',
            'author' => 'Market Analyst',
            'published_at' => '2023-08-15 10:00:00',
            'excerpt' => "This week's market showed significant movement in tech stocks, with the NASDAQ index reaching new heights.",
            'content' => "This week's market showed significant movement in tech stocks, with the NASDAQ index reaching new heights. Simultaneously, energy commodities experienced volatility due to global supply chain challenges. The S&P 500 closed at a record high on Thursday, driven by strong earnings reports from major technology companies.",
            'feature_image' => 'https://via.placeholder.com/800x400',
            'category_name' => 'Market Analysis'
        ],
        [
            'id' => 2,
            'title' => 'Investment Strategies for Beginners',
            'slug' => 'investment-strategies',
            'author' => 'Financial Advisor',
            'published_at' => '2023-08-10 14:30:00',
            'excerpt' => "Starting your investment journey can be overwhelming. This guide breaks down essential strategies that new investors should consider.",
            'content' => "Starting your investment journey can be overwhelming. This guide breaks down essential strategies that new investors should consider, focusing on diversification and long-term planning. Understanding your risk tolerance is the first step in creating a successful investment strategy.",
            'feature_image' => 'https://via.placeholder.com/800x400',
            'category_name' => 'Investment'
        ],
        [
            'id' => 3,
            'title' => 'Cryptocurrency Trends: What\'s Next?',
            'slug' => 'crypto-trends',
            'author' => 'Crypto Analyst',
            'published_at' => '2023-08-05 09:15:00',
            'excerpt' => "The cryptocurrency market continues to evolve at a rapid pace. This analysis examines current trends and potential future developments.",
            'content' => "The cryptocurrency market continues to evolve at a rapid pace. This analysis examines current trends and potential future developments in the digital asset space. Institutional adoption has been a key driver of cryptocurrency growth in recent months.",
            'feature_image' => 'https://via.placeholder.com/800x400',
            'category_name' => 'Cryptocurrency'
        ]
    ];
}

// Get total post count for pagination
$total_posts = !empty($posts) ? count($posts) : 0;
if (function_exists('get_post_count')) {
    $total_posts = get_post_count();
}
$total_pages = ceil($total_posts / $posts_per_page);

// Get categories for sidebar (or use static ones if database fails)
$categories = [];
if (function_exists('get_categories')) {
    $categories = get_categories();
}
if (empty($categories)) {
    $categories = [
        ['id' => 1, 'name' => 'Market Analysis', 'slug' => 'market-analysis'],
        ['id' => 2, 'name' => 'Investment', 'slug' => 'investment'],
        ['id' => 3, 'name' => 'Cryptocurrency', 'slug' => 'cryptocurrency']
    ];
}

// Get recent posts for sidebar
$recent_posts = $posts;
if (function_exists('get_recent_posts')) {
    $recent_posts = get_recent_posts(5);
}
if (empty($recent_posts)) {
    $recent_posts = array_slice($posts, 0, 3);
}

// Helper function to format dates
if (!function_exists('format_date')) {
    function format_date($date_string) {
        $date = new DateTime($date_string);
        return $date->format('F j, Y');
    }
}

// Helper function to escape HTML
if (!function_exists('h')) {
    function h($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - My Website</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Welcome to My Website</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="blog.html" class="active">Blog</a></li>
            </ul>
        </nav>
    </header>

    <main class="blog-page">
        <section class="blog-hero">
            <h2>Our Blog</h2>
            <p>Stay updated with our latest insights, analyses, and market reviews.</p>
        </section>

        <section class="blog-content">
            <div class="blog-container full-width">
                <?php if (empty($posts)): ?>
                    <div class="no-posts">
                        <p>No posts found. Check back soon for new content!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <article class="blog-post full-post">
                            <h3><?= h($post['title']) ?></h3>
                            <p class="blog-meta">Posted on <?= format_date($post['published_at']) ?> | By <?= h($post['author']) ?></p>
                            
                            <?php if (!empty($post['feature_image'])): ?>
                                <div class="blog-image">
                                    <img src="<?= h($post['feature_image']) ?>" alt="<?= h($post['title']) ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="blog-excerpt">
                                <p><?= h($post['excerpt']) ?></p>
                                
                                <?php if (!empty($post['content'])): ?>
                                    <?php 
                                    // Display a preview of the content (first 300 characters)
                                    $content_preview = substr(strip_tags($post['content']), 0, 300);
                                    if (strlen(strip_tags($post['content'])) > 300) {
                                        $content_preview .= '...';
                                    }
                                    ?>
                                    <p><?= h($content_preview) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($post['slug'])): ?>
                                <a href="blog/<?= h($post['slug']) ?>.html" class="read-more">Read Full Article</a>
                            <?php else: ?>
                                <a href="post.php?id=<?= h($post['id']) ?>" class="read-more">Read Full Article</a>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <section class="blog-pagination">
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>" class="page-nav">← Previous</a>
                    <?php endif; ?>
                    
                    <?php 
                    // Show limited page numbers with ellipsis
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);
                    
                    if ($start_page > 1): ?>
                        <a href="?page=1" class="page-link">1</a>
                        <?php if ($start_page > 2): ?>
                            <span class="page-ellipsis">...</span>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="current-page"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?page=<?= $i ?>" class="page-link"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($end_page < $total_pages): ?>
                        <?php if ($end_page < $total_pages - 1): ?>
                            <span class="page-ellipsis">...</span>
                        <?php endif; ?>
                        <a href="?page=<?= $total_pages ?>" class="page-link"><?= $total_pages ?></a>
                    <?php endif; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>" class="page-nav">Next →</a>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2023 My Website. All rights reserved.</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html> 