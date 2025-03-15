<?php
require_once 'config.php';

// Get all blog posts
function get_posts($limit = 10, $offset = 0, $status = 'published') {
    $db = db_connect();
    $query = "SELECT p.*, c.name as category_name 
              FROM posts p
              JOIN categories c ON p.category = c.slug 
              WHERE status = :status 
              ORDER BY published_at DESC 
              LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
    $stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
    
    $result = $stmt->execute();
    $posts = [];
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $posts[] = $row;
    }
    
    $db->close();
    return $posts;
}

// Get total post count
function get_post_count($status = 'published') {
    $db = db_connect();
    $query = "SELECT COUNT(*) as count FROM posts WHERE status = :status";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    
    $db->close();
    return $row['count'];
}

// Get a single post by slug
function get_post_by_slug($slug) {
    $db = db_connect();
    $query = "SELECT p.*, c.name as category_name 
              FROM posts p
              JOIN categories c ON p.category = c.slug 
              WHERE p.slug = :slug";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':slug', $slug, SQLITE3_TEXT);
    
    $result = $stmt->execute();
    $post = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($post) {
        // Get post tags
        $query = "SELECT t.name, t.slug FROM tags t
                  JOIN post_tags pt ON t.id = pt.tag_id
                  WHERE pt.post_id = :post_id";
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(':post_id', $post['id'], SQLITE3_INTEGER);
        
        $result = $stmt->execute();
        $tags = [];
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $tags[] = $row;
        }
        
        $post['tags'] = $tags;
    }
    
    $db->close();
    return $post;
}

// Get a single post by ID
function get_post_by_id($id) {
    $db = db_connect();
    $query = "SELECT * FROM posts WHERE id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    
    $result = $stmt->execute();
    $post = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($post) {
        // Get post tags
        $query = "SELECT t.id, t.name, t.slug FROM tags t
                  JOIN post_tags pt ON t.id = pt.tag_id
                  WHERE pt.post_id = :post_id";
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(':post_id', $post['id'], SQLITE3_INTEGER);
        
        $result = $stmt->execute();
        $tags = [];
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $tags[] = $row;
        }
        
        $post['tags'] = $tags;
    }
    
    $db->close();
    return $post;
}

// Create a new blog post
function create_post($data) {
    $db = db_connect();
    
    // Generate slug if not provided
    if (empty($data['slug'])) {
        $data['slug'] = create_slug($data['title']);
    }
    
    // Handle feature image upload
    $feature_image = null;
    if (isset($_FILES['feature_image']) && $_FILES['feature_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = UPLOADS_DIR;
        $temp_file = $_FILES['feature_image']['tmp_name'];
        $file_name = time() . '_' . $_FILES['feature_image']['name'];
        $upload_path = $upload_dir . '/' . $file_name;
        
        if (move_uploaded_file($temp_file, $upload_path)) {
            $feature_image = UPLOADS_URL . '/' . $file_name;
        }
    }
    
    // Set published date if status is published
    $published_at = ($data['status'] === 'published') ? date('Y-m-d H:i:s') : null;
    
    $query = "INSERT INTO posts (title, slug, content, excerpt, feature_image, author, category, status, published_at)
              VALUES (:title, :slug, :content, :excerpt, :feature_image, :author, :category, :status, :published_at)";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':title', $data['title'], SQLITE3_TEXT);
    $stmt->bindValue(':slug', $data['slug'], SQLITE3_TEXT);
    $stmt->bindValue(':content', $data['content'], SQLITE3_TEXT);
    $stmt->bindValue(':excerpt', $data['excerpt'], SQLITE3_TEXT);
    $stmt->bindValue(':feature_image', $feature_image, SQLITE3_TEXT);
    $stmt->bindValue(':author', $data['author'], SQLITE3_TEXT);
    $stmt->bindValue(':category', $data['category'], SQLITE3_TEXT);
    $stmt->bindValue(':status', $data['status'], SQLITE3_TEXT);
    $stmt->bindValue(':published_at', $published_at, SQLITE3_TEXT);
    
    $result = $stmt->execute();
    $post_id = $db->lastInsertRowID();
    
    // Add tags if provided
    if (isset($data['tags']) && is_array($data['tags'])) {
        foreach ($data['tags'] as $tag_id) {
            $query = "INSERT INTO post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':post_id', $post_id, SQLITE3_INTEGER);
            $stmt->bindValue(':tag_id', $tag_id, SQLITE3_INTEGER);
            $stmt->execute();
        }
    }
    
    $db->close();
    return $post_id;
}

// Update an existing blog post
function update_post($id, $data) {
    $db = db_connect();
    
    // Get existing post
    $post = get_post_by_id($id);
    if (!$post) {
        return false;
    }
    
    // Handle feature image upload
    $feature_image = $post['feature_image']; // Keep existing image by default
    if (isset($_FILES['feature_image']) && $_FILES['feature_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = UPLOADS_DIR;
        $temp_file = $_FILES['feature_image']['tmp_name'];
        $file_name = time() . '_' . $_FILES['feature_image']['name'];
        $upload_path = $upload_dir . '/' . $file_name;
        
        if (move_uploaded_file($temp_file, $upload_path)) {
            $feature_image = UPLOADS_URL . '/' . $file_name;
        }
    }
    
    // Update published date if status changed to published
    $published_at = $post['published_at'];
    if ($post['status'] !== 'published' && $data['status'] === 'published') {
        $published_at = date('Y-m-d H:i:s');
    }
    
    $query = "UPDATE posts SET 
              title = :title,
              slug = :slug,
              content = :content,
              excerpt = :excerpt,
              feature_image = :feature_image,
              author = :author,
              category = :category,
              status = :status,
              updated_at = CURRENT_TIMESTAMP,
              published_at = :published_at
              WHERE id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':title', $data['title'], SQLITE3_TEXT);
    $stmt->bindValue(':slug', $data['slug'], SQLITE3_TEXT);
    $stmt->bindValue(':content', $data['content'], SQLITE3_TEXT);
    $stmt->bindValue(':excerpt', $data['excerpt'], SQLITE3_TEXT);
    $stmt->bindValue(':feature_image', $feature_image, SQLITE3_TEXT);
    $stmt->bindValue(':author', $data['author'], SQLITE3_TEXT);
    $stmt->bindValue(':category', $data['category'], SQLITE3_TEXT);
    $stmt->bindValue(':status', $data['status'], SQLITE3_TEXT);
    $stmt->bindValue(':published_at', $published_at, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    
    $result = $stmt->execute();
    
    // Clear existing tags
    $query = "DELETE FROM post_tags WHERE post_id = :post_id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':post_id', $id, SQLITE3_INTEGER);
    $stmt->execute();
    
    // Add new tags
    if (isset($data['tags']) && is_array($data['tags'])) {
        foreach ($data['tags'] as $tag_id) {
            $query = "INSERT INTO post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':post_id', $id, SQLITE3_INTEGER);
            $stmt->bindValue(':tag_id', $tag_id, SQLITE3_INTEGER);
            $stmt->execute();
        }
    }
    
    $db->close();
    return true;
}

// Delete a blog post
function delete_post($id) {
    $db = db_connect();
    
    // Get post to check for feature image
    $post = get_post_by_id($id);
    if ($post && $post['feature_image']) {
        $image_path = __DIR__ . '/..' . $post['feature_image'];
        if (file_exists($image_path)) {
            unlink($image_path); // Delete the feature image file
        }
    }
    
    // Delete post
    $query = "DELETE FROM posts WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    // Post tags will be deleted automatically due to ON DELETE CASCADE
    
    $db->close();
    return $result ? true : false;
}

// Get all categories
function get_categories() {
    $db = db_connect();
    $query = "SELECT * FROM categories ORDER BY name";
    $result = $db->query($query);
    
    $categories = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $categories[] = $row;
    }
    
    $db->close();
    return $categories;
}

// Get all tags
function get_tags() {
    $db = db_connect();
    $query = "SELECT * FROM tags ORDER BY name";
    $result = $db->query($query);
    
    $tags = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $tags[] = $row;
    }
    
    $db->close();
    return $tags;
}

// Get recent posts for sidebar
function get_recent_posts($limit = 5) {
    return get_posts($limit);
}

// Get posts by category
function get_posts_by_category($category_slug, $limit = 10, $offset = 0) {
    $db = db_connect();
    $query = "SELECT p.*, c.name as category_name 
              FROM posts p
              JOIN categories c ON p.category = c.slug 
              WHERE p.category = :category AND p.status = 'published'
              ORDER BY published_at DESC 
              LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':category', $category_slug, SQLITE3_TEXT);
    $stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
    $stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
    
    $result = $stmt->execute();
    $posts = [];
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $posts[] = $row;
    }
    
    $db->close();
    return $posts;
}

// Get posts by tag
function get_posts_by_tag($tag_slug, $limit = 10, $offset = 0) {
    $db = db_connect();
    $query = "SELECT p.*, c.name as category_name 
              FROM posts p
              JOIN categories c ON p.category = c.slug 
              JOIN post_tags pt ON p.id = pt.post_id
              JOIN tags t ON pt.tag_id = t.id
              WHERE t.slug = :tag AND p.status = 'published'
              ORDER BY p.published_at DESC 
              LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':tag', $tag_slug, SQLITE3_TEXT);
    $stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
    $stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
    
    $result = $stmt->execute();
    $posts = [];
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $posts[] = $row;
    }
    
    $db->close();
    return $posts;
}

// Search posts
function search_posts($term, $limit = 10, $offset = 0) {
    $db = db_connect();
    $search_term = "%$term%";
    
    $query = "SELECT p.*, c.name as category_name 
              FROM posts p
              JOIN categories c ON p.category = c.slug 
              WHERE (p.title LIKE :term OR p.content LIKE :term) AND p.status = 'published'
              ORDER BY p.published_at DESC 
              LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':term', $search_term, SQLITE3_TEXT);
    $stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
    $stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
    
    $result = $stmt->execute();
    $posts = [];
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $posts[] = $row;
    }
    
    $db->close();
    return $posts;
} 