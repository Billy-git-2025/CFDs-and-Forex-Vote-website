<?php
/**
 * Central blog data repository
 * 
 * This file serves as a single source of truth for blog posts,
 * which can be used by both the dynamic PHP version and for 
 * generating static HTML versions of the blog.
 */

// Define the blog posts array
function get_blog_posts() {
    return [
        [
            'id' => 1,
            'title' => 'Gold Surges to New Record Near $2,990 as Crude Oil Declines',
            'slug' => 'gold-record-oil-decline',
            'author' => 'Market Analyst',
            'published_at' => '2023-10-15 10:00:00',
            'excerpt' => "Gold prices have reached unprecedented heights, climbing to nearly $2,990 per ounce, while crude oil markets experienced a significant decline of over 1%.",
            'content' => "Gold prices have reached unprecedented heights, climbing to nearly $2,990 per ounce, while crude oil markets experienced a significant decline of over 1%. This divergence highlights the complex interplay of economic factors currently influencing commodity markets.",
            'feature_image' => 'https://via.placeholder.com/800x400',
            'category_name' => 'Market Analysis'
        ],
        [
            'id' => 2,
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
            'id' => 3,
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
            'id' => 4,
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

// Helper function to get post by ID
function get_post_by_id($id) {
    $posts = get_blog_posts();
    foreach ($posts as $post) {
        if ($post['id'] == $id) {
            return $post;
        }
    }
    return null;
}

// Helper function to get post by slug
function get_post_by_slug($slug) {
    $posts = get_blog_posts();
    foreach ($posts as $post) {
        if ($post['slug'] == $slug) {
            return $post;
        }
    }
    return null;
}

// Helper function to format date
function format_blog_date($date_string) {
    $date = new DateTime($date_string);
    return $date->format('F j, Y');
} 