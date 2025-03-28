<?php
/**
 * Static Blog Generator
 * 
 * This script generates a static HTML blog page from the centralized blog data.
 * Run this script whenever blog data is updated to keep the static HTML in sync.
 *
 * Usage: php tools/generate_static_blog.php
 */

// Include the blog data
require_once __DIR__ . '/../includes/blog_data.php';

// Get all blog posts
$posts = get_blog_posts();

// Start building the HTML content
$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trading Insights Blog | Market Sentiment Hub</title>
    <meta name="description" content="Explore the latest analysis, trends, and strategies for CFDs, Forex, and Cryptocurrency trading in our regularly updated blog.">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Font Awesome for Discord icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
        }
        
        .site-header {
            background-color: #ffffff;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .site-title {
            color: #333;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            margin: 0;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .site-title:hover {
            color: #2763c5;
        }
        
        .site-nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .site-nav li {
            margin-left: 1.5rem;
        }
        
        .site-nav a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .site-nav a:hover,
        .site-nav a.active {
            color: #2763c5;
        }
        
        .discord-link {
            color: #7289DA; /* Discord color */
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: opacity 0.2s;
        }
        .discord-link:hover {
            opacity: 0.8;
        }
        .discord-icon {
            font-size: 1.2em;
        }
        .read-more {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: #dbc106;
            color: black;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .read-more:hover {
            background-color: #c4a905;
        }
        
        main {
            margin-top: 80px;
            padding: 2rem;
        }
        
        /* For smaller screens, make the nav more responsive */
        @media (max-width: 768px) {
            header nav ul {
                gap: 15px;
            }
            
            .site-nav li {
                margin-left: 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <a href="/" class="site-title">Market Sentiment Hub</a>
        <nav class="site-nav">
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/blog.html" class="active">Media</a></li>
                <li><a href="/compare.html">Compare</a></li>
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
                <nav class="site-nav">
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/blog.html" class="active">Media</a></li>
                        <li><a href="/compare.html">Compare</a></li>
                    </ul>
                </nav>
HTML;

// Add each blog post to the HTML
foreach ($posts as $post) {
    $date = format_blog_date($post['published_at']);
    $html .= <<<HTML
                <article class="blog-post full-post">
                    <h3>{$post['title']}</h3>
                    <p class="blog-meta">Posted on {$date} | By {$post['author']}</p>
                    <div class="blog-image">
                        <img src="{$post['feature_image']}" alt="{$post['title']}">
                    </div>
                    <div class="blog-excerpt">
                        <p>{$post['excerpt']}</p>
                    </div>
                    <a href="blog/{$post['slug']}.html" class="read-more">Read Full Article</a>
                </article>

HTML;
}

// Complete the HTML
$html .= <<<HTML
            </div>
        </section>

        <section class="blog-pagination">
            <div class="pagination">
                <span class="current-page">1</span>
                <a href="#" class="page-link">2</a>
                <a href="#" class="page-link">3</a>
                <a href="#" class="next-page">Next →</a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Market Sentiment Hub. All rights reserved.</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
HTML;

// Write the HTML to the blog.html file
$file = __DIR__ . '/../blog.html';
file_put_contents($file, $html);

echo "Static blog.html has been generated successfully!\n";

// Generate a README explaining how to use this script
$readme = <<<README
# Blog Synchronization

## Overview
This directory contains tools to keep your HTML and PHP blog versions synchronized.

## Usage

When you add or update blog posts:

1. Update the central blog data in `includes/blog_data.php`
2. Run this script to regenerate the static HTML: 
   ```
   php tools/generate_static_blog.php
   ```
3. This will ensure that blog.html reflects the latest posts in your blog data.

## Workflow

1. Add new blog posts to the `get_blog_posts()` function in `includes/blog_data.php`
2. Create the individual blog post HTML file in the `/blog` directory
3. Run this script to update the blog listing page
4. Commit all changes to your repository

## Automated Option

You can also set up a Git hook to run this automatically before commits:

1. Create a pre-commit hook in `.git/hooks/pre-commit`:
   ```bash
   #!/bin/sh
   php tools/generate_static_blog.php
   git add blog.html
   ```

2. Make the hook executable:
   ```bash
   chmod +x .git/hooks/pre-commit
   ```
README;

// Write the README to the tools directory
$readmeFile = __DIR__ . '/README.md';
file_put_contents($readmeFile, $readme);

echo "README has been created in the tools directory.\n"; 