# Application Form Plugin for WordPress

A comprehensive WordPress plugin for managing course applications with document uploads, email notifications, and admin management interface.

## Features

- **Complete Registration Form** with fields:
  - Full Name
  - NRIC No
  - Contact No
  - Email Address
  - Home Address
  - Course Interested (dropdown selection)

- **Document Upload System** for:
  - NRIC Front Copy (Certified True Copy)
  - NRIC Back Copy (Certified True Copy)
  - Birth Certificate Copy (Certified True Copy)
  - Bank Statement Front Page (Certified True Copy)

- **Admin Management Interface**
  - View all applications in a filterable list
  - View individual application details
  - Update application status (Pending, Approved, Rejected, Under Review)
  - Add notes to applications
  - Export applications to CSV
  - Delete applications

- **Email Notifications**
  - Admin notification for new applications
  - Applicant confirmation email
  - Status update notifications

- **Security Features**
  - Nonce verification
  - File type validation (PDF, JPG, PNG only)
  - File size limit (5MB per file)
  - Sanitized inputs
  - Secure file storage
  - IP address logging

- **Modern UI**
  - Responsive design
  - Clean and professional appearance
  - AJAX form submission
  - Real-time validation
  - Progress indicators

## Installation

1. **Upload the Plugin**
   - Download the `application-form-plugin` folder
   - Upload it to `/wp-content/plugins/` directory
   - Or upload as a ZIP file via WordPress admin panel

2. **Activate the Plugin**
   - Go to WordPress Admin > Plugins
   - Find "Application Form Plugin"
   - Click "Activate"

3. **Configure Settings**
   - Go to "Application Forms" > "Settings" in admin menu
   - Set admin email for notifications
   - Enable/disable email notifications

## Usage

### Display the Form

Use the shortcode on any page or post:

```
[application_form]
```

**With custom title:**

```
[application_form title="Course Registration Form"]
```

### Managing Applications

1. **View Applications**
   - Go to WordPress Admin > Application Forms
   - Filter by status: All, Pending, Approved, Rejected
   - Search by name, email, NRIC, or contact number

2. **View Application Details**
   - Click "View" on any application
   - See all submitted information and documents
   - Download uploaded documents
   - Update status and add notes

3. **Export Data**
   - Click "Export to CSV" button
   - Download all applications or filtered results

### Email Notifications

The plugin automatically sends emails:

- **To Admin**: When a new application is submitted
- **To Applicant**: Confirmation email upon submission
- **To Applicant**: When application status is updated

## File Structure

```
application-form-plugin/
├── application-form-plugin.php     # Main plugin file
├── includes/
│   ├── class-database.php          # Database operations
│   ├── class-form-handler.php      # Form rendering and processing
│   ├── class-admin-page.php        # Admin interface
│   └── class-email-handler.php     # Email notifications
├── assets/
│   ├── css/
│   │   ├── frontend.css            # Frontend styles
│   │   └── admin.css               # Admin styles
│   └── js/
│       ├── frontend.js             # Frontend JavaScript
│       └── admin.js                # Admin JavaScript
└── README.md                       # This file
```

## Database Schema

The plugin creates a table `wp_afp_applications` with the following structure:

- `id` - Unique application ID
- `full_name` - Applicant's full name
- `nric_no` - NRIC number
- `contact_no` - Contact number
- `email` - Email address
- `home_address` - Home address
- `course_interested` - Selected course
- `nric_front_file` - NRIC front file name
- `nric_back_file` - NRIC back file name
- `birth_certificate_file` - Birth certificate file name
- `bank_statement_file` - Bank statement file name
- `status` - Application status
- `notes` - Admin notes
- `ip_address` - Submitter's IP
- `user_agent` - Browser information
- `submitted_date` - Submission timestamp
- `updated_date` - Last update timestamp

## File Upload Requirements

- **Accepted formats**: PDF, JPG, JPEG, PNG
- **Maximum file size**: 5MB per file
- **Storage location**: `/wp-content/uploads/application-documents/`
- **Security**: Protected with .htaccess rules

## Customization

### Adding Custom Courses

Edit the course dropdown in `includes/class-form-handler.php`:

```php
<select id="course_interested" name="course_interested" class="afp-select" required>
    <option value="">Select a course</option>
    <option value="Your Course Name">Your Course Name</option>
    <!-- Add more courses here -->
</select>
```

### Customizing Email Templates

Edit email templates in `includes/class-email-handler.php`:

- `get_admin_email_template()` - Admin notification email
- `get_applicant_email_template()` - Applicant confirmation email

### Styling Customization

- **Frontend styles**: `assets/css/frontend.css`
- **Admin styles**: `assets/css/admin.css`

You can override styles in your theme's CSS file.

## Shortcode Parameters

| Parameter | Description | Default |
|-----------|-------------|---------|
| `title` | Form title | "Course Application Form" |
| `redirect` | Redirect URL after submission | (empty) |

**Example:**

```
[application_form title="Apply Now" redirect="https://yoursite.com/thank-you"]
```

## Troubleshooting

### Forms Not Submitting

1. Check if jQuery is loaded
2. Check browser console for JavaScript errors
3. Verify nonce is being generated correctly
4. Check file permissions on upload directory

### Files Not Uploading

1. Verify upload directory exists: `/wp-content/uploads/application-documents/`
2. Check directory permissions (755 or 775)
3. Verify PHP `upload_max_filesize` is at least 5MB
4. Check PHP `post_max_size` is at least 25MB (for multiple files)

### Emails Not Sending

1. Check WordPress email settings
2. Install WP Mail SMTP plugin for better email delivery
3. Verify admin email in plugin settings
4. Check email notifications are enabled in settings

### Database Table Not Created

1. Deactivate and reactivate the plugin
2. Check database user permissions
3. Verify WordPress database connection

## Security Considerations

- All inputs are sanitized and validated
- File uploads are restricted by type and size
- Files are stored outside web root where possible
- Nonce verification on all forms
- Admin capabilities required for management
- SQL injection protection via prepared statements
- XSS protection via output escaping

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- MySQL 5.6 or higher
- jQuery (included with WordPress)

## Support

For support, feature requests, or bug reports:

- Email: [your-email@example.com]
- Website: [https://yoursite.com]

## License

This plugin is licensed under GPL v2 or later.

## Changelog

### Version 1.0.0
- Initial release
- Complete application form with document uploads
- Admin management interface
- Email notifications
- CSV export functionality
- Responsive design

## Credits

Developed by [Your Name]

---

**Note**: This plugin stores uploaded documents in `/wp-content/uploads/application-documents/`. Make sure to backup this directory regularly along with the database table.
