-- Blog posts table
CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    content TEXT NOT NULL,
    excerpt TEXT,
    feature_image TEXT,
    author TEXT NOT NULL,
    category TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'draft',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    published_at DATETIME
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    slug TEXT NOT NULL UNIQUE,
    description TEXT
);

-- Tags table
CREATE TABLE IF NOT EXISTS tags (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    slug TEXT NOT NULL UNIQUE
);

-- Post tags relationship (many-to-many)
CREATE TABLE IF NOT EXISTS post_tags (
    post_id INTEGER,
    tag_id INTEGER,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Users table for admin access
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    role TEXT NOT NULL DEFAULT 'author',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT OR IGNORE INTO users (username, password, email, role)
VALUES ('admin', '$2y$10$5Wl3uO2oE5O7j53H0O5KL.mFZUqAO9h.6QRj1bhIEESVd9m0qL5ke', 'admin@example.com', 'admin');

-- Insert default categories
INSERT OR IGNORE INTO categories (name, slug, description) 
VALUES 
('Market Analysis', 'market-analysis', 'Posts analyzing current market trends and conditions'),
('Investment Strategies', 'investment-strategies', 'Advice and strategies for better investing'),
('Trading Tips', 'trading-tips', 'Tips and tricks for successful trading'),
('Financial News', 'financial-news', 'Latest news from the financial world');

-- Insert sample tags
INSERT OR IGNORE INTO tags (name, slug)
VALUES 
('Markets', 'markets'),
('Stocks', 'stocks'),
('Crypto', 'crypto'),
('Investing', 'investing'),
('Beginners', 'beginners'),
('Analysis', 'analysis'),
('Diversification', 'diversification'),
('Financial Planning', 'financial-planning'),
('Tech Stocks', 'tech-stocks'),
('Energy', 'energy'); 