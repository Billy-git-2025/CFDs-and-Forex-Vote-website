# Blog Synchronization Guide

## Overview

This website uses both static HTML and PHP for blog content. To ensure consistency between them, we've implemented a centralized system for blog data management.

## How It Works

1. **Central Data Source**: All blog posts are defined in `includes/blog_data.php`
2. **PHP Blog**: The `blog.php` file uses this data source directly
3. **Static HTML**: The `blog.html` file should be regenerated when content changes
4. **Individual Blog Posts**: Each post has its own HTML file in the `/blog` directory

## Adding New Blog Posts

When you want to add a new blog post:

1. **Update the central data**:
   - Add your new post to the `get_blog_posts()` array in `includes/blog_data.php`
   - Follow the existing format, providing all required fields

2. **Create the individual blog post file**:
   - Create a new HTML file in the `/blog` directory with the format `your-slug.html`
   - Make sure the slug matches what you specified in the central data

3. **Regenerate the static blog page**:
   - Run `php tools/generate_static_blog.php` to update blog.html
   - If PHP isn't available on command line, manually update blog.html

## Manual Update Process (if needed)

If you can't run the PHP script, ensure blog.html is updated manually to include:

1. The new blog post in the same format as existing posts
2. The correct title, date, author, and excerpt
3. A link to the individual blog post file

## Importance of Synchronization

Keeping content in sync is essential because:
- Some users may access the PHP version, while others see the static HTML
- Search engines might index either version
- Inconsistent content creates a confusing user experience

## Troubleshooting

If you notice discrepancies between blog.php and blog.html:
1. Verify both are using the latest data from `includes/blog_data.php`
2. Re-run the generator script or manually update blog.html
3. Check that all individual blog post files exist and are linked correctly

By following this process, you can ensure your blog content remains consistent across the entire website. 