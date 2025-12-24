# Installation Guide

## Quick Installation (5 Minutes)

### Step 1: Upload Plugin

**Option A: Via WordPress Admin (Recommended)**

1. Download the plugin as a ZIP file
2. Log in to WordPress Admin
3. Go to `Plugins` > `Add New`
4. Click `Upload Plugin` button
5. Choose the ZIP file
6. Click `Install Now`
7. Click `Activate Plugin`

**Option B: Via FTP/File Manager**

1. Extract the ZIP file on your computer
2. Upload the `application-form-plugin` folder to `/wp-content/plugins/`
3. Go to WordPress Admin > Plugins
4. Find "Application Form Plugin" and click `Activate`

### Step 2: Verify Installation

After activation, you should see:
- New menu item "Application Forms" in WordPress admin sidebar
- Success message confirming activation

### Step 3: Configure Settings

1. Go to `Application Forms` > `Settings`
2. Set your admin email address (for receiving notifications)
3. Enable/disable email notifications
4. Click `Save Settings`

### Step 4: Create a Page for the Form

1. Go to `Pages` > `Add New`
2. Give it a title (e.g., "Apply Now" or "Course Application")
3. In the content area, add the shortcode:
   ```
   [application_form]
   ```
4. Publish the page
5. View the page to see your form

## Detailed Configuration

### Email Setup

For best email delivery, we recommend:

1. **Install WP Mail SMTP Plugin** (optional but recommended)
   - Helps ensure emails are delivered
   - Prevents emails from going to spam
   - Free plugin available in WordPress repository

2. **Configure Email Settings**
   - Go to `Application Forms` > `Settings`
   - Enter a valid email address
   - Test by submitting a test application

### File Upload Directory

The plugin automatically creates `/wp-content/uploads/application-documents/`

**To verify:**
1. Go to your hosting file manager or FTP
2. Navigate to `/wp-content/uploads/`
3. Look for `application-documents` folder
4. Check permissions are set to 755 or 775

**If directory doesn't exist:**
```bash
# Via SSH
cd /path/to/wordpress/wp-content/uploads/
mkdir application-documents
chmod 755 application-documents
```

### Database Table

The plugin creates a table named `wp_afp_applications` (prefix may vary)

**To verify:**
1. Open phpMyAdmin or your database manager
2. Look for table `wp_afp_applications`
3. Should see columns for applicant data

**If table doesn't exist:**
1. Deactivate the plugin
2. Activate it again
3. This will trigger table creation

## Server Requirements

### Minimum Requirements

- **PHP Version**: 7.2 or higher (7.4+ recommended)
- **WordPress Version**: 5.0 or higher (latest recommended)
- **MySQL Version**: 5.6 or higher (8.0+ recommended)
- **Memory Limit**: 64MB minimum (128MB+ recommended)
- **Upload Max Filesize**: 5MB minimum (10MB+ recommended)
- **Post Max Size**: 25MB minimum (for multiple file uploads)

### Checking PHP Settings

Add this to a file called `info.php` in your WordPress root:

```php
<?php phpinfo(); ?>
```

Visit: `https://yoursite.com/info.php`

Look for:
- `upload_max_filesize` - should be at least 5M
- `post_max_size` - should be at least 25M
- `max_execution_time` - should be at least 60
- `memory_limit` - should be at least 128M

**Delete `info.php` after checking!**

### Updating PHP Settings

**Via php.ini (if you have access):**
```ini
upload_max_filesize = 10M
post_max_size = 30M
max_execution_time = 60
memory_limit = 128M
```

**Via .htaccess (in WordPress root):**
```apache
php_value upload_max_filesize 10M
php_value post_max_size 30M
php_value max_execution_time 60
php_value memory_limit 128M
```

**Via wp-config.php:**
```php
define('WP_MEMORY_LIMIT', '128M');
```

## Troubleshooting Installation

### Plugin Won't Activate

**Error: "Plugin could not be activated"**

1. Check PHP version (needs 7.2+)
2. Check file permissions
3. Check for conflicting plugins
4. Enable WordPress debug mode:
   ```php
   // Add to wp-config.php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```
5. Check `/wp-content/debug.log` for errors

### Database Table Not Created

1. Check database user has CREATE TABLE permission
2. Try deactivating and reactivating plugin
3. Check database prefix matches WordPress installation
4. Manually create table using SQL in phpMyAdmin

### Upload Directory Not Created

1. Check `wp-content/uploads/` exists and is writable
2. Check folder permissions (should be 755 or 775)
3. Create folder manually via FTP/File Manager
4. Set correct ownership (usually www-data or your hosting user)

### White Screen After Activation

1. Increase PHP memory limit
2. Check for PHP errors in server logs
3. Disable all other plugins
4. Switch to default WordPress theme
5. Re-upload plugin files (may be corrupted)

### Permission Errors

```bash
# Fix file permissions (via SSH)
cd /path/to/wordpress/wp-content/plugins/application-form-plugin
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

# Fix upload directory
chmod 755 /path/to/wordpress/wp-content/uploads/application-documents
```

## Testing Installation

### Test Checklist

- [ ] Plugin activates without errors
- [ ] "Application Forms" menu appears in admin
- [ ] Settings page loads correctly
- [ ] Form displays on frontend page
- [ ] Form accepts valid input
- [ ] File uploads work (all 4 documents)
- [ ] Form submission completes successfully
- [ ] Admin receives notification email
- [ ] Applicant receives confirmation email
- [ ] Application appears in admin list
- [ ] Can view application details
- [ ] Can update application status
- [ ] CSV export works

### Test Application Data

Use this test data to verify functionality:

```
Full Name: John Doe
NRIC No: 123456-12-1234
Contact No: +60123456789
Email: john.doe@example.com
Home Address: 123 Main Street, City, State, 12345
Course: Diploma in Information Technology
```

**Test Files:**
- Create 4 small PDF or image files
- Name them clearly (nric_front.pdf, etc.)
- Upload all 4 files
- Submit form

**Expected Result:**
- Success message appears
- Form clears
- 2 emails sent (admin + applicant)
- Application visible in admin panel

## Security Checklist

After installation, verify:

- [ ] Upload directory has `.htaccess` file
- [ ] Direct PHP file access is blocked
- [ ] File type restrictions are working
- [ ] File size limits are enforced
- [ ] Nonce verification is working
- [ ] SQL injection protection is active
- [ ] XSS protection is active

## Next Steps

1. **Customize Course Options**
   - Edit course list in form handler
   - Add your institution's courses

2. **Test Email Delivery**
   - Submit test applications
   - Check spam folders
   - Consider SMTP plugin

3. **Style Customization**
   - Adjust colors in CSS files
   - Match your site's branding
   - Test responsive design

4. **User Training**
   - Train staff on admin interface
   - Create workflow documentation
   - Set up status update procedures

5. **Backup Setup**
   - Backup database regularly
   - Backup upload directory
   - Test restore procedures

## Support Resources

If you encounter issues:

1. Check the main README.md file
2. Review error logs
3. Check WordPress.org support forums
4. Contact plugin developer

## Uninstallation

To completely remove the plugin:

1. Go to `Plugins` page
2. Deactivate the plugin
3. Click `Delete`

**This will:**
- Drop the database table
- Delete all uploaded documents
- Remove plugin options
- Cannot be undone!

**Before uninstalling:**
- Export applications to CSV
- Backup uploaded documents manually
- Save any important data

---

**Installation complete!** You're ready to start receiving applications.
