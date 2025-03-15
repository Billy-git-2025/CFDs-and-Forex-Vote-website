<?php
require_once 'includes/config.php';
require_once 'includes/blog.php';

// Initialize database if needed
init_database();

// Get post slug from URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Get post data
$post = get_post_by_slug($slug);

// Redirect to blog list if post not found
if (!$post) {
    header('Location: blog.php');
    exit;
}

// Get categories for sidebar
$categories = get_categories();

// Get recent posts for sidebar
$recent_posts = get_recent_posts(5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($post['title']) ?> - My Website</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/blog.css">
</head>
<body>
    <header>
        <h1>My Website</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="blog.php" class="active">Blog</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="blog-post-container">
        <article class="blog-post-full">
            <div class="blog-header">
                <h1><?= h($post['title']) ?></h1>
                <p class="blog-meta">
                    Posted on <?= format_date($post['published_at']) ?> | 
                    By <?= h($post['author']) ?> | 
                    Category: <a href="category.php?slug=<?= h($post['category']) ?>"><?= h($post['category_name']) ?></a>
                </p>
            </div>
            
            <?php if (!empty($post['feature_image'])): ?>
                <div class="blog-feature-image">
                    <img src="<?= h($post['feature_image']) ?>" alt="<?= h($post['title']) ?>">
                </div>
            <?php endif; ?>
            
            <div class="blog-content">
                <?= nl2br(h($post['content'])) ?>
            </div>
            
            <div class="blog-footer">
                <?php if (!empty($post['tags'])): ?>
                    <div class="tags">
                        <?php foreach ($post['tags'] as $tag): ?>
                            <a href="tag.php?slug=<?= h($tag['slug']) ?>" class="tag"><?= h($tag['name']) ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="share-buttons">
                    <span>Share:</span>
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>&text=<?= urlencode($post['title']) ?>" target="_blank" class="share-button">Twitter</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" class="share-button">Facebook</a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>&title=<?= urlencode($post['title']) ?>" target="_blank" class="share-button">LinkedIn</a>
                </div>
            </div>
        </article>
        
        <aside class="blog-sidebar">
            <div class="sidebar-section">
                <h3>Categories</h3>
                <ul>
                    <?php foreach ($categories as $category): ?>
                        <li><a href="category.php?slug=<?= h($category['slug']) ?>"><?= h($category['name']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="sidebar-section">
                <h3>Recent Posts</h3>
                <ul>
                    <?php foreach ($recent_posts as $recent): ?>
                        <li><a href="post.php?slug=<?= h($recent['slug']) ?>"><?= h($recent['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="sidebar-section">
                <h3>Search</h3>
                <form action="search.php" method="get" class="search-form">
                    <input type="text" name="q" placeholder="Search blog...">
                    <button type="submit">Search</button>
                </form>
            </div>
        </aside>
    </div>

    <footer>
        <p>&copy; 2023 My Website. All rights reserved.</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html> 