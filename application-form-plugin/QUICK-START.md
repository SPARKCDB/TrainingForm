# Quick Start Guide

Get your application form running in 5 minutes!

## 1. Install & Activate (2 minutes)

### Upload Plugin
1. Go to WordPress Admin
2. Navigate to `Plugins` > `Add New`
3. Click `Upload Plugin`
4. Choose the ZIP file
5. Click `Install Now` then `Activate`

✅ You should see "Application Forms" in your admin menu

## 2. Configure Settings (1 minute)

1. Click `Application Forms` > `Settings`
2. Enter your email address
3. Check "Enable email notifications"
4. Click `Save Settings`

✅ You'll now receive notifications for new applications

## 3. Add Form to Page (1 minute)

1. Go to `Pages` > `Add New`
2. Title: "Apply Now"
3. Content: `[application_form]`
4. Click `Publish`

✅ Your form is now live!

## 4. Test the Form (1 minute)

1. Visit your new page
2. Fill in all fields
3. Upload 4 test files (PDF or images)
4. Click Submit

✅ Check your email for notification

## 5. View Application (30 seconds)

1. Go to `Application Forms` in admin
2. Click `View` on the test application
3. Review all submitted data

✅ You're ready to receive real applications!

---

## Using the Shortcode

### Basic Usage
```
[application_form]
```

### With Custom Title
```
[application_form title="Course Registration Form"]
```

### Where to Use It
- ✅ Pages
- ✅ Posts
- ✅ Custom Post Types
- ✅ Widgets (if your theme supports shortcodes in widgets)

---

## Managing Applications

### View All Applications
`Application Forms` menu → See list of all submissions

### Filter by Status
- All
- Pending (new applications)
- Approved
- Rejected
- Under Review

### Search Applications
Use search box to find by:
- Name
- Email
- NRIC
- Contact number

### Update Status
1. Click `View` on any application
2. Change status dropdown
3. Add notes (optional)
4. Click `Update Status`

### Export Data
Click `Export to CSV` button to download all applications

---

## Common Customizations

### Change Form Title
When using shortcode:
```
[application_form title="Your Custom Title"]
```

### Add Different Courses
Edit file: `includes/class-form-handler.php`
Look for: `<select id="course_interested"`
Add your courses as options

### Change Colors
Edit file: `assets/css/frontend.css`
Look for color codes (e.g., `#0073aa`)
Replace with your brand colors

### Modify Email Template
Edit file: `includes/class-email-handler.php`
Look for: `get_admin_email_template` or `get_applicant_email_template`

---

## Troubleshooting Quick Fixes

### Form Not Showing
- Check if shortcode is correct: `[application_form]`
- Try clearing your browser cache
- Check if plugin is activated

### Files Won't Upload
- Check file size (must be under 5MB each)
- Check file type (PDF, JPG, PNG only)
- Check upload directory exists: `/wp-content/uploads/application-documents/`

### No Email Received
- Check spam/junk folder
- Verify email address in settings
- Check "Enable notifications" is checked
- Consider installing WP Mail SMTP plugin

### Application Not Saving
- Check database connection
- Check table exists: `wp_afp_applications`
- Try deactivating and reactivating plugin

---

## Best Practices

### 1. Regular Backups
- Export applications to CSV weekly
- Backup `/wp-content/uploads/application-documents/` folder
- Backup WordPress database

### 2. Email Configuration
- Use a proper SMTP plugin for better delivery
- Use a business email address (not free email)
- Test email delivery regularly

### 3. Security
- Keep WordPress and plugin updated
- Use strong passwords for admin accounts
- Regular security scans
- Monitor upload directory

### 4. Organization
- Set up a workflow for processing applications
- Respond to applications within 24-48 hours
- Keep notes on each application
- Use status updates consistently

### 5. Communication
- Send confirmation emails promptly
- Keep applicants updated on status changes
- Set clear expectations for response time
- Provide contact information for questions

---

## Quick Reference

### Important Folders
```
/wp-content/plugins/application-form-plugin/     (Plugin files)
/wp-content/uploads/application-documents/       (Uploaded documents)
```

### Important Files
```
application-form-plugin.php           (Main plugin file)
includes/class-form-handler.php       (Form logic)
assets/css/frontend.css               (Form styling)
```

### Database Table
```
wp_afp_applications                   (All applications)
```

### Admin Pages
```
Application Forms > All Applications  (View list)
Application Forms > Settings          (Configure)
```

### Shortcode
```
[application_form]                    (Display form)
```

---

## Support Workflow

### For Applicants

**Pending** → Application submitted, awaiting review
**Under Review** → Currently being processed
**Approved** → Application accepted
**Rejected** → Application declined

### For Administrators

1. **New Application Arrives**
   - Email notification sent
   - Status: Pending

2. **Start Review**
   - Open application
   - Review documents
   - Change status to "Under Review"

3. **Make Decision**
   - Approve or Reject
   - Add notes explaining decision
   - Applicant receives email notification

4. **Archive**
   - Export old applications
   - Delete if needed
   - Keep backup

---

## Next Steps

Now that your form is running:

1. ✅ Test with real data
2. ✅ Train your team
3. ✅ Share the page link
4. ✅ Monitor applications daily
5. ✅ Respond promptly

**Need Help?**
- Check the main README.md
- Review INSTALLATION.md
- Check WordPress debug log
- Contact support

---

**Congratulations!** Your application form is ready to use. 🎉
