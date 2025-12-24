# Training Application Form Plugin

A complete WordPress plugin for managing course applications with document uploads.

## 📦 What's Included

This repository contains a fully functional WordPress plugin that provides:

- **Complete Application Form** with all required fields (Full Name, NRIC, Contact, Email, Address, Course)
- **Document Upload System** for 4 certified documents (NRIC Front/Back, Birth Certificate, Bank Statement)
- **Admin Management Interface** to view, manage, and export applications
- **Email Notifications** for both administrators and applicants
- **Security Features** including file validation, sanitization, and nonce verification
- **Responsive Design** that works on all devices
- **CSV Export** functionality for data management

## 🚀 Quick Start

1. **Copy the plugin folder** to your WordPress installation:
   ```bash
   cp -r application-form-plugin /path/to/wordpress/wp-content/plugins/
   ```

2. **Activate the plugin** via WordPress Admin → Plugins

3. **Add the form** to any page using the shortcode:
   ```
   [application_form]
   ```

## 📁 Plugin Structure

```
application-form-plugin/
├── application-form-plugin.php     # Main plugin file
├── includes/                       # Core functionality
│   ├── class-database.php          # Database operations
│   ├── class-form-handler.php      # Form logic
│   ├── class-admin-page.php        # Admin interface
│   └── class-email-handler.php     # Email system
├── assets/                         # Frontend resources
│   ├── css/                        # Stylesheets
│   └── js/                         # JavaScript
├── README.md                       # Main documentation
├── INSTALLATION.md                 # Detailed installation guide
├── QUICK-START.md                  # 5-minute setup guide
├── CHANGELOG.md                    # Version history
└── LICENSE.txt                     # GPL v2 License
```

## 🎯 Features

### For Applicants
- Easy-to-use online application form
- Drag-and-drop file uploads (PDF, JPG, PNG)
- Real-time form validation
- Instant confirmation email
- Mobile-friendly interface

### For Administrators
- View all applications in one place
- Filter by status (Pending, Approved, Rejected)
- Search by name, email, NRIC, or contact
- Update application status with notes
- Export applications to CSV
- Email notifications for new submissions

### Security
- Nonce verification on all forms
- File type and size validation
- SQL injection protection
- XSS protection
- Secure file storage
- IP address logging

## 📖 Documentation

- **[README.md](application-form-plugin/README.md)** - Complete feature documentation
- **[INSTALLATION.md](application-form-plugin/INSTALLATION.md)** - Detailed installation instructions
- **[QUICK-START.md](application-form-plugin/QUICK-START.md)** - Get started in 5 minutes
- **[CHANGELOG.md](application-form-plugin/CHANGELOG.md)** - Version history and updates

## ⚙️ Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- MySQL 5.6 or higher
- 5MB+ upload file size limit

## 🔧 Configuration

1. Go to **Application Forms → Settings**
2. Set your admin email address
3. Enable/disable email notifications
4. Customize as needed

## 📝 Usage

### Display Form
```
[application_form]
```

### Custom Title
```
[application_form title="Apply for Courses"]
```

### Manage Applications
Go to **Application Forms** in WordPress admin to:
- View all submissions
- Update application status
- Add notes
- Export to CSV

## 🎨 Customization

### Change Courses
Edit `includes/class-form-handler.php` and modify the course options in the dropdown.

### Style Customization
Edit `assets/css/frontend.css` to match your branding.

### Email Templates
Modify templates in `includes/class-email-handler.php`.

## 🐛 Troubleshooting

**Form not showing?**
- Check shortcode spelling: `[application_form]`
- Clear browser cache
- Verify plugin is activated

**Files not uploading?**
- Check file size (max 5MB)
- Check file type (PDF, JPG, PNG only)
- Verify upload directory permissions

**No emails received?**
- Check spam folder
- Verify email in settings
- Install WP Mail SMTP plugin

See [INSTALLATION.md](application-form-plugin/INSTALLATION.md) for more troubleshooting.

## 🔒 Security

- All inputs are sanitized
- Files are validated before upload
- Nonce verification on all actions
- SQL prepared statements
- Secure file storage
- Regular security updates recommended

## 📊 Data Management

### Backup
Regularly backup:
- WordPress database (`wp_afp_applications` table)
- Upload directory: `/wp-content/uploads/application-documents/`

### Export
Use the CSV export feature to download all applications.

## 📄 License

GPL v2 or later - See [LICENSE.txt](application-form-plugin/LICENSE.txt)

## 🤝 Support

For issues or questions:
1. Check the documentation files
2. Review WordPress debug logs
3. Contact plugin developer

## 🎉 Credits

Developed for course application management with focus on security, usability, and functionality.

---

**Ready to deploy!** Follow the [QUICK-START.md](application-form-plugin/QUICK-START.md) guide to get your application form live in 5 minutes.
