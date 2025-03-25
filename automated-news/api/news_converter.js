/**
 * News Converter
 * Converts news items to HTML using the template
 */

// In a real implementation, these would be replaced with actual file reading
const fs = require('fs');
const path = require('path');

/**
 * Loads the HTML template
 * @returns {string} HTML template
 */
function loadTemplate() {
  // In a real implementation, this would read from the template file
  // const templatePath = path.join(__dirname, '../templates/news-post.html');
  // return fs.readFileSync(templatePath, 'utf8');
  
  // For now, return a simplified template
  return `
<!DOCTYPE html>
<html>
<head>
  <title>{{TITLE}} - Market Sentiment Hub</title>
</head>
<body>
  <h1>{{TITLE}}</h1>
  <p>{{DATE}}</p>
  <div>{{CONTENT}}</div>
  <p>Source: <a href="{{SOURCE_URL}}">{{SOURCE_NAME}}</a></p>
</body>
</html>
  `.trim();
}

/**
 * Formats a date for display
 * @param {string} isoDate - ISO date string
 * @returns {string} Formatted date
 */
function formatDate(isoDate) {
  const date = new Date(isoDate);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
}

/**
 * Converts a news item to HTML
 * @param {Object} newsItem - News item object
 * @returns {string} HTML content
 */
function convertToHtml(newsItem) {
  const template = loadTemplate();
  
  // Format the content as paragraphs
  const paragraphs = newsItem.description.split('\n')
    .map(p => `<p>${p}</p>`)
    .join('\n');
  
  // Replace template placeholders
  return template
    .replace(/{{TITLE}}/g, newsItem.title)
    .replace(/{{DESCRIPTION}}/g, newsItem.description.substring(0, 160) + '...')
    .replace(/{{DATE}}/g, formatDate(newsItem.timestamp))
    .replace(/{{CONTENT}}/g, paragraphs)
    .replace(/{{SOURCE_URL}}/g, newsItem.url)
    .replace(/{{SOURCE_NAME}}/g, 'AASTOCKS');
}

/**
 * Saves the HTML content to a file
 * @param {string} html - HTML content
 * @param {string} filename - Filename to save as
 * @returns {Promise<void>}
 */
async function saveHtml(html, filename) {
  // In a real implementation, this would write to a file
  // const outputPath = path.join(__dirname, '../../blog', filename);
  // await fs.promises.writeFile(outputPath, html);
  
  console.log(`HTML content would be saved to: ${filename}`);
}

/**
 * Processes news items and converts them to HTML
 * @param {Array} newsItems - Array of news items
 * @returns {Promise<void>}
 */
async function processNewsItems(newsItems) {
  try {
    for (const item of newsItems) {
      // Create a slug from the title
      const slug = item.title
        .toLowerCase()
        .replace(/[^\w\s]/g, '')
        .replace(/\s+/g, '-');
      
      const filename = `${slug}.html`;
      const html = convertToHtml(item);
      
      await saveHtml(html, filename);
    }
    
    console.log(`Processed ${newsItems.length} news items`);
  } catch (error) {
    console.error('Error processing news items:', error);
  }
}

module.exports = {
  convertToHtml,
  processNewsItems
}; 