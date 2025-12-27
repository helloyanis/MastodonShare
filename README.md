# MastodonShare

A simple web application that provides a "share to Mastodon" endpoint. Users can enter their Mastodon instance and are redirected to that instance's sharing page with pre-filled text and URL.

This project powers https://mastodonshare.🦊💻.ws

## 🚀 Features

- **Instance Selection**: Users can select their preferred Mastodon instance
- **Memory**: Remembers the chosen instance to avoid repeated selection
- **Easy share link creation**: Create a text directly from the web UI and share the link online!
- **Direct Redirect**: Redirects users straight to their instance's share page
- **Clean UI**: Material-based styling with responsive design
- **Local only**: No data stored on any server, is cached by your browser at 1st start and only needs JavaScript to work! This means no hosting costs!
- **Backwards compatible with mastodonshare.com** : Just change the domain name to share with one or the other!

## 📋 Requirements

- Modern web browser with JavaScript enabled

## 🛠️ Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/mastodonshare.git
   cd mastodonshare
   ```

2. **Set up your web server**:
   - Serve the index.html file

4. **Test the installation**:
   - Visit your domain in a web browser
   - You should see the MastodonShare interface

## 📖 Usage

### For End Users

1. **Direct Sharing**: Visit the site with parameters:
   ```
   https://yoursite.com/?text=Hello%20Mastodon
   ```

2. **Manual Entry**: Use the web form to enter your content and select your Mastodon instance

3. **Instance Selection**: Choose your preferred Mastodon instance (saved in local storage)

### For Developers

#### API Parameters

- `text`: The text content to share
- `url`: The URL to share
- `note`: Optional additional notes
- `instance`: Force a specific Mastodon instance

#### Example API Usage

```javascript
// Share content to Mastodon
const shareUrl = `https://mastodonshare.com/?text=${encodeURIComponent('Check this out!')}&url=${encodeURIComponent('https://example.com')}`;
window.open(shareUrl, '_blank');
```

# 🔒 Security consniderations

Everything happens client-side

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Inspired by the need for easy Mastodon sharing
- Thanks to the Mastodon community for feedback and suggestions and Alex Barredo for the original idea

## 📞 Support

If you encounter any issues or have questions:

1. Check the [Issues](https://github.com/helloyanis/mastodonshare/issues) page
2. Create a new issue with detailed information
3. Contact the maintainers

---

**Note**: This application is designed to be simple and lightweight. No database is required, and it can be tested on any browser.
