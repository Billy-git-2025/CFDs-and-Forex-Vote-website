# API Security Guidelines

## Protecting API Keys

This project uses a secure approach to handle API keys and other sensitive credentials. 
Follow these guidelines to maintain security when working with the codebase.

### Setup for New Developers

1. Copy the template config file to create your working config:
   ```
   cp js/config.template.js js/config.js
   ```

2. Contact the project administrator to get the actual API keys

3. Update your local `js/config.js` file with the real credentials

4. Verify that `js/config.js` is listed in `.gitignore` to prevent accidental commits

### Adding New API Keys

When adding new API services:

1. Never commit API keys or secrets directly to the code
2. Update both the template file and your local config file
3. Document the new keys in the project documentation
4. Share keys with team members through secure channels (not via email or chat)

### Security Breach Response

If you discover that API keys have been exposed:

1. Immediately notify the project administrator
2. Rotate (change) the compromised keys in the service provider's dashboard
3. Update all local config files with the new keys
4. Review commit history to ensure no other credentials are exposed

### Best Practices

- Consider using environment variables for production deployments
- Implement API key rotation on a regular schedule
- Use API key restrictions where possible (IP, referrer, or service limitations)
- Consider implementing server-side proxies for API calls in production

## Firebase Specific Settings

For added security with Firebase:
1. In the Firebase Console, restrict your API key:
   - Go to Project Settings → API keys
   - Add application restrictions (HTTP referrers)
   - Limit which Firebase services the key can access

2. Set up proper Firebase Security Rules to restrict database access 