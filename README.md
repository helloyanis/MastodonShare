# MastodonShare

A simple PHP web application that provides a "share to Mastodon" endpoint. Users can enter their Mastodon instance and are redirected to that instance's sharing page with pre-filled text and URL.

This project powers [https://mastodonshare.com](https://mastodonshare.com)

## 🚀 Features

- **Instance Selection**: Users can select their preferred Mastodon instance
- **Cookie Memory**: Remembers the chosen instance to avoid repeated selection
- **Pre-filled Sharing**: Accepts `text`, `url`, and optional `note` parameters
- **Direct Redirect**: Redirects users straight to their instance's share page
- **Clean UI**: Bootstrap-based styling with responsive design
- **Session Management**: Handles user sessions for seamless experience

## 📋 Requirements

- PHP 7.0 or higher
- Web server (Apache, Nginx, etc.)
- Modern web browser with JavaScript enabled

## 🛠️ Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/mastodonshare.git
   cd mastodonshare
   ```

2. **Configure the application**:
   - Copy `m/config.php` and modify it for your environment
   - Update the following settings:
     - `DOMAIN`: Your domain name
     - `URL`: Your application's base URL
     - Database settings (if applicable)

3. **Set up your web server**:
   - Point your web server's document root to the project directory
   - Ensure PHP is properly configured
   - Make sure the `m/` directory is accessible

4. **Test the installation**:
   - Visit your domain in a web browser
   - You should see the MastodonShare interface

## 🔧 Configuration

The main configuration file is located at `m/config.php`. Key configuration options:

```php
// Domain configuration
define('DOMAIN', 'yourdomain.com');
define('URL', 'https://yourdomain.com');

// Cookie settings
define('COOKIE_SECURE', true);
define('COOKIE_HTTPONLY', true);
```

## 📖 Usage

### For End Users

1. **Direct Sharing**: Visit the site with parameters:
   ```
   https://yoursite.com/?text=Hello%20Mastodon&url=https://example.com
   ```

2. **Manual Entry**: Use the web form to enter your content and select your Mastodon instance

3. **Instance Selection**: Choose your preferred Mastodon instance (saved in cookies)

### For Developers

#### API Parameters

- `text`: The text content to share
- `url`: The URL to share
- `note`: Optional additional notes
- `instance`: Force a specific Mastodon instance

#### Example API Usage

```javascript
// Share content to Mastodon
const shareUrl = `https://yoursite.com/?text=${encodeURIComponent('Check this out!')}&url=${encodeURIComponent('https://example.com')}`;
window.open(shareUrl, '_blank');
```

## 🏗️ Project Structure

```
mastodonshare/
├── index.php          # Main entry point and router
├── README.md          # This file
├── html/              # HTML templates and views
│   ├── about.php      # About page
│   ├── create.php     # Share creation form
│   ├── instance.php   # Instance selection
│   ├── redirect.php   # Redirect logic
│   ├── share.php      # Share page
│   ├── shareform.php  # Share form
│   └── inc_*.php      # Include files (header, footer, etc.)
├── m/                 # Core application logic
│   ├── config.php     # Configuration settings
│   └── general.php    # Utility functions
└── s/                 # Static assets (CSS, JS, images)
```

## 🔒 Security Considerations

- Input validation and sanitization
- Secure cookie handling
- XSS protection through `htmlspecialchars()`
- CSRF protection (implement as needed)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Built with PHP and Bootstrap
- Inspired by the need for easy Mastodon sharing
- Thanks to the Mastodon community for feedback and suggestions

## 📞 Support

If you encounter any issues or have questions:

1. Check the [Issues](https://github.com/yourusername/mastodonshare/issues) page
2. Create a new issue with detailed information
3. Contact the maintainers

---

**Note**: This application is designed to be simple and lightweight. No database is required, and it can be deployed on any standard PHP hosting environment.
