<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/blog.php';

// Initialize database if needed
init_database();

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

// Get current user
$current_user = get_current_user();

// Get post counts
$published_count = get_post_count('published');
$draft_count = get_post_count('draft');
$scheduled_count = get_post_count('scheduled');
$total_count = $published_count + $draft_count + $scheduled_count;

// Get recent posts
$recent_posts = get_posts(5, 0, 'published');

// Get categories
$categories = get_categories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - My Website</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <h1>Blog Admin Panel</h1>
        <nav>
            <ul>
                <li><a href="../index.html">View Site</a></li>
                <li><a href="index.php" class="active">Dashboard</a></li>
                <li><a href="posts.php">Posts</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-user">
                <div class="user-avatar"><?= substr($current_user['username'], 0, 1) ?></div>
                <div class="user-details">
                    <h3><?= h($current_user['username']) ?></h3>
                    <p><?= h($current_user['role']) ?></p>
                </div>
            </div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="index.php" class="active">Dashboard</a></li>
                    <li><a href="posts.php">Posts</a></li>
                    <li><a href="post-new.php">Add New Post</a></li>
                    <li><a href="categories.php">Categories</a></li>
                    <li><a href="tags.php">Tags</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>

        <div class="admin-content">
            <div class="admin-header">
                <h2>Dashboard</h2>
                <a href="post-new.php" class="btn-primary">Add New Post</a>
            </div>

            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-value"><?= $total_count ?></div>
                    <div class="stat-label">Total Posts</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $published_count ?></div>
                    <div class="stat-label">Published</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $draft_count ?></div>
                    <div class="stat-label">Drafts</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $scheduled_count ?></div>
                    <div class="stat-label">Scheduled</div>
                </div>
            </div>

            <div class="dashboard-sections">
                <div class="dashboard-section">
                    <h3>Recent Posts</h3>
                    <?php if (empty($recent_posts)): ?>
                        <p>No posts yet. <a href="post-new.php">Create your first post</a>.</p>
                    <?php else: ?>
                        <ul class="recent-posts-list">
                            <?php foreach ($recent_posts as $post): ?>
                                <li>
                                    <a href="post-edit.php?id=<?= $post['id'] ?>"><?= h($post['title']) ?></a>
                                    <span class="post-date"><?= format_date($post['published_at']) ?></span>
                                    <span class="post-author"><?= h($post['author']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                
                <div class="dashboard-section">
                    <h3>Categories</h3>
                    <?php if (empty($categories)): ?>
                        <p>No categories yet. <a href="categories.php">Add categories</a>.</p>
                    <?php else: ?>
                        <ul class="categories-list">
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <a href="categories.php?edit=<?= $category['id'] ?>"><?= h($category['name']) ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <div class="dashboard-quick-draft">
                <h3>Quick Draft</h3>
                <form action="post-new.php" method="post" class="quick-draft-form">
                    <div class="form-group">
                        <label for="draft-title">Title</label>
                        <input type="text" id="draft-title" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="draft-content">Content</label>
                        <textarea id="draft-content" name="content" rows="5" required></textarea>
                    </div>
                    
                    <input type="hidden" name="status" value="draft">
                    <input type="hidden" name="quick_draft" value="1">
                    
                    <div class="form-group">
                        <button type="submit" class="btn-save">Save Draft</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2023 My Website. All rights reserved.</p>
    </footer>

    <script src="../js/main.js"></script>
</body>
</html> 