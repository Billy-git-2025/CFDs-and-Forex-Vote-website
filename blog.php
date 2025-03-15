<?php
require_once 'includes/config.php';
require_once 'includes/blog.php';

// Initialize database if needed
init_database();

// Get page number for pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1
$posts_per_page = 6;
$offset = ($page - 1) * $posts_per_page;

// Get posts for current page
$posts = get_posts($posts_per_page, $offset);

// Get total post count for pagination
$total_posts = get_post_count();
$total_pages = ceil($total_posts / $posts_per_page);

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
    <title>Blog - My Website</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/blog.css">
</head>
<body>
    <header>
        <h1>My Website</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="#" class="active">Blog</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="blog-container-main">
        <main class="blog-main">
            <h1 class="page-title">Latest Blog Posts</h1>
            
            <div class="blog-grid">
                <?php if (empty($posts)): ?>
                    <div class="no-posts">
                        <p>No posts found.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <article class="blog-card">
                            <?php if (!empty($post['feature_image'])): ?>
                                <div class="blog-image">
                                    <img src="<?= h($post['feature_image']) ?>" alt="<?= h($post['title']) ?>">
                                </div>
                            <?php endif; ?>
                            <div class="blog-content">
                                <h2><a href="post.php?slug=<?= h($post['slug']) ?>"><?= h($post['title']) ?></a></h2>
                                <p class="blog-meta">
                                    <span class="blog-category"><?= h($post['category_name']) ?></span>
                                    <span class="blog-date"><?= format_date($post['published_at']) ?></span>
                                </p>
                                <p class="blog-excerpt"><?= h($post['excerpt']) ?></p>
                                <a href="post.php?slug=<?= h($post['slug']) ?>" class="read-more">Read More</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>" class="page-nav">&laquo; Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?>" class="page-num <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>" class="page-nav">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
        
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