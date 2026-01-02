# TrainingForm

A modern, responsive training application form published on GitHub Pages.

## 🌐 Live Demo

**Form URL:** `https://sparkcdb.github.io/TrainingForm/`

## 📋 Overview

This is a comprehensive training application form that collects:
- Personal Information (Name, NRIC, Contact, Email, Address)
- Course Selection
- Required Documents (NRIC Front/Back, Birth Certificate, Bank Statement)

## ✨ Features

- **Modern & Responsive Design** - Works seamlessly on desktop, tablet, and mobile devices
- **Real-time Validation** - Instant feedback on form inputs
- **File Upload Validation** - Ensures correct file types (PDF, JPG, PNG) and sizes (max 5MB)
- **Professional UI** - Clean, intuitive interface with smooth animations
- **Accessible** - Built with web accessibility best practices
- **Security** - Client-side validation and sanitization

## 🚀 Deployment

This form is automatically deployed to GitHub Pages using GitHub Actions.

### Automatic Deployment

Every push to the `cursor/form-github-io-deployment-cad3` or `Sihat` branch triggers an automatic deployment to GitHub Pages.

### Manual Setup (if needed)

1. Go to repository Settings → Pages
2. Under "Build and deployment":
   - Source: GitHub Actions
3. The workflow will automatically deploy on the next push

## 📁 Project Structure

```
TrainingForm/
├── index.html          # Main form HTML
├── styles.css          # Modern, responsive styling
├── script.js           # Form validation and handling
├── .github/
│   └── workflows/
│       └── deploy.yml  # GitHub Actions deployment workflow
└── README.md           # This file
```

## 🔧 Backend Integration

The form currently uses a simulated submission for demonstration. To integrate with a real backend:

### Option 1: Formspree (Recommended for quick setup)
1. Sign up at [formspree.io](https://formspree.io)
2. Create a new form and get your endpoint URL
3. Update `script.js` - uncomment and configure the Formspree fetch code

### Option 2: Google Forms
1. Create a Google Form with matching fields
2. Map form fields to Google Form entry IDs
3. Update submission logic in `script.js`

### Option 3: Custom API Backend
1. Create your backend API (Node.js, Python, PHP, etc.)
2. Set up file upload handling
3. Update the fetch URL in `script.js`
4. Configure CORS if needed

### Option 4: Serverless Functions
- **Netlify Functions** - If hosting on Netlify
- **Vercel Serverless** - If hosting on Vercel
- **AWS Lambda** - For AWS-based solutions

## 📧 Email Notifications

For email notifications on form submissions, consider:
- [EmailJS](https://www.emailjs.com/) - Client-side email sending
- [SendGrid](https://sendgrid.com/) - Transactional email service
- [Mailgun](https://www.mailgun.com/) - Email automation

## 📦 File Upload Services

For handling file uploads (since GitHub Pages is static):
- [Cloudinary](https://cloudinary.com/) - Media management
- [AWS S3](https://aws.amazon.com/s3/) - Cloud storage
- [Firebase Storage](https://firebase.google.com/products/storage) - Google's storage solution
- [Uploadcare](https://uploadcare.com/) - File uploading service

## 🛠️ Local Development

1. Clone the repository:
   ```bash
   git clone https://github.com/SPARKCDB/TrainingForm.git
   cd TrainingForm
   ```

2. Open `index.html` in your browser or use a local server:
   ```bash
   # Using Python
   python -m http.server 8000
   
   # Using Node.js
   npx serve
   
   # Using PHP
   php -S localhost:8000
   ```

3. Access at `http://localhost:8000`

## 🎨 Customization

### Modify Form Fields
Edit `index.html` to add/remove fields or change labels.

### Update Styling
Modify `styles.css` - CSS variables are defined at the top for easy theming:
```css
:root {
    --primary-color: #2563eb;
    --primary-hover: #1d4ed8;
    /* ... more variables */
}
```

### Change Course Options
Update the `<select id="course">` options in `index.html`.

### Adjust Validation Rules
Modify the `validationRules` object in `script.js`.

## 🔒 Security Notes

- All validation is currently client-side (for demonstration)
- **Important:** Add server-side validation when integrating with a backend
- Sanitize all inputs on the server
- Use HTTPS for production
- Implement rate limiting
- Store uploaded files securely
- Never trust client-side validation alone

## 📱 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## 📄 License

This project is available for use under standard GitHub terms.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## 📞 Support

For issues or questions:
- Create an issue in this repository
- Contact: support@sparkcdb.org

## 🎯 Related Projects

This form is also available as:
- **WordPress Plugin** - See branch `cursor/wordpress-application-form-plugin-d041`

## 📝 Changelog

### Version 1.0.0 (2026-01-02)
- Initial release
- Responsive form design
- Real-time validation
- File upload support
- GitHub Pages deployment

---

**Built with ❤️ by SPARK CDB**

*Ready to publish to GitHub Pages!*
