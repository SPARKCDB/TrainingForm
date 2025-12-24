# 🚀 How to Deploy Your WordPress Application Form Plugin

## Quick Overview

You now have a **complete WordPress plugin** in the `application-form-plugin/` folder. This guide will help you deploy it to your WordPress site.

---

## 📦 Step 1: Prepare the Plugin

### Option A: Use Directly (if your WordPress is on this server)
```bash
# Copy to WordPress plugins directory
cp -r application-form-plugin /path/to/wordpress/wp-content/plugins/

# Set correct permissions
chmod -R 755 /path/to/wordpress/wp-content/plugins/application-form-plugin
```

### Option B: Create ZIP for Upload
```bash
# Create ZIP file
cd /workspace
zip -r application-form-plugin.zip application-form-plugin/

# Download the ZIP file to your computer
# Then upload via WordPress Admin
```

---

## 🌐 Step 2: Install on WordPress

### Method 1: Via WordPress Admin (Recommended)

1. **Download/Transfer** the plugin folder to your computer as a ZIP file
2. **Login** to your WordPress Admin panel
3. **Navigate** to: Plugins → Add New → Upload Plugin
4. **Choose** the ZIP file
5. **Click** "Install Now"
6. **Click** "Activate Plugin"

### Method 2: Via FTP/File Manager

1. **Connect** to your hosting via FTP or File Manager
2. **Navigate** to: `/wp-content/plugins/`
3. **Upload** the entire `application-form-plugin` folder
4. **Go to** WordPress Admin → Plugins
5. **Find** "Application Form Plugin"
6. **Click** "Activate"

---

## ⚙️ Step 3: Configure Settings

1. **Go to:** WordPress Admin → Application Forms → Settings
2. **Enter:** Your admin email address (where you want to receive notifications)
3. **Check:** "Enable email notifications"
4. **Click:** "Save Settings"

✅ Configuration complete!

---

## 📝 Step 4: Create Application Page

1. **Go to:** WordPress Admin → Pages → Add New
2. **Title:** "Apply Now" (or whatever you prefer)
3. **Content:** Add this shortcode:
   ```
   [application_form]
   ```
4. **Click:** "Publish"
5. **Copy** the page URL (e.g., `https://yoursite.com/apply-now/`)

✅ Your form is now live!

---

## 🧪 Step 5: Test Everything

### Test the Form
1. **Visit** your application page
2. **Fill in** all fields with test data:
   - Full Name: Test User
   - NRIC No: 123456-12-1234
   - Contact: +60123456789
   - Email: your-test-email@example.com
   - Address: 123 Test Street
   - Course: Select any course
3. **Upload** 4 test files (PDF or images, under 5MB each)
4. **Click** "Submit Application"
5. **Verify** success message appears

### Test Admin Panel
1. **Go to:** WordPress Admin → Application Forms
2. **Verify** your test application appears
3. **Click** "View" to see details
4. **Check** all files are accessible
5. **Try** changing status to "Approved"
6. **Click** "Update Status"

### Test Emails
1. **Check** your admin email inbox
2. **Look for** "New Course Application Received"
3. **Check** test email inbox
4. **Look for** "Application Received" confirmation
5. **If no emails:** Check spam folder, verify settings

✅ If all tests pass, you're ready!

---

## 🎨 Step 6: Customize (Optional)

### Change Course Options
**File:** `application-form-plugin/includes/class-form-handler.php`
**Line:** Around 65-75
**Look for:** `<select id="course_interested"`
**Edit:** Add/remove/modify course options

### Change Colors
**File:** `application-form-plugin/assets/css/frontend.css`
**Find & Replace:** `#0073aa` with your brand color

### Customize Form Title
**In your page shortcode:**
```
[application_form title="Your Custom Title Here"]
```

---

## 📊 Step 7: Start Receiving Applications

### Daily Tasks
- ✅ Check Application Forms menu for new submissions
- ✅ Review documents
- ✅ Update status (Pending → Under Review → Approved/Rejected)
- ✅ Add notes for internal tracking

### Weekly Tasks
- ✅ Export applications to CSV (backup)
- ✅ Respond to all pending applications
- ✅ Archive processed applications

### Monthly Tasks
- ✅ Backup database and upload folder
- ✅ Review and optimize workflow
- ✅ Check for WordPress/plugin updates

---

## 🆘 Common Issues & Solutions

### "Plugin could not be activated"
**Cause:** PHP version too old or missing files
**Solution:** 
- Check PHP version (needs 7.2+)
- Re-upload all plugin files
- Contact hosting support

### "Form not showing on page"
**Cause:** Shortcode misspelled or cache issue
**Solution:**
- Check shortcode: `[application_form]` (no typos)
- Clear browser cache
- Clear WordPress cache

### "Files won't upload"
**Cause:** File too large or wrong type
**Solution:**
- Check file is under 5MB
- Check file is PDF, JPG, or PNG
- Check upload folder permissions
- Ask hosting to increase `upload_max_filesize`

### "No emails received"
**Cause:** Email delivery issue
**Solution:**
- Check spam folder
- Verify email address in settings
- Install "WP Mail SMTP" plugin
- Contact hosting about email setup

### "Database error"
**Cause:** Table not created
**Solution:**
- Deactivate plugin
- Reactivate plugin (recreates table)
- Check database permissions

---

## 📂 Important Files & Locations

### Plugin Files
```
/wp-content/plugins/application-form-plugin/
```

### Uploaded Documents
```
/wp-content/uploads/application-documents/
```

### Database Table
```
wp_afp_applications
(prefix may vary based on your WordPress installation)
```

---

## 🔒 Security Checklist

After deployment, verify:
- ✅ WordPress is up to date
- ✅ Strong admin password set
- ✅ SSL certificate active (HTTPS)
- ✅ Regular backups scheduled
- ✅ Upload directory not publicly browsable
- ✅ Database user has minimal permissions
- ✅ WordPress debug mode OFF in production

---

## 📚 Need More Help?

### Documentation Available
1. **QUICK-START.md** - 5-minute setup guide
2. **INSTALLATION.md** - Detailed installation instructions
3. **README.md** - Complete feature documentation
4. **DEPLOYMENT-CHECKLIST.md** - Production deployment guide
5. **SCREENSHOTS.md** - UI reference and layouts

### Troubleshooting Steps
1. Check WordPress debug log: `/wp-content/debug.log`
2. Enable debug mode in `wp-config.php`:
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```
3. Check browser console for JavaScript errors (F12)
4. Test with all other plugins disabled
5. Switch to default WordPress theme temporarily

---

## 🎯 Success Checklist

- [ ] Plugin uploaded to WordPress
- [ ] Plugin activated successfully
- [ ] Settings configured (email address set)
- [ ] Page created with shortcode
- [ ] Test application submitted successfully
- [ ] Test application appears in admin panel
- [ ] Email notifications received
- [ ] Documents uploaded successfully
- [ ] Status can be updated
- [ ] CSV export works
- [ ] Form tested on mobile device
- [ ] Backup system configured
- [ ] Team trained on usage

---

## 🎉 You're Done!

Your WordPress Application Form Plugin is now live and ready to receive applications!

### Next Steps:
1. Share the application page URL with potential applicants
2. Monitor submissions daily
3. Respond to applications promptly
4. Keep backups up to date
5. Update WordPress and plugins regularly

---

## 📞 Quick Command Reference

### Create ZIP for upload
```bash
cd /workspace
zip -r application-form-plugin.zip application-form-plugin/
```

### Copy to WordPress
```bash
cp -r application-form-plugin /path/to/wordpress/wp-content/plugins/
```

### Set permissions
```bash
chmod -R 755 /path/to/wordpress/wp-content/plugins/application-form-plugin
```

### Create backup
```bash
# Backup database table
mysqldump -u username -p database_name wp_afp_applications > backup.sql

# Backup upload folder
tar -czf documents-backup.tar.gz /path/to/wp-content/uploads/application-documents/
```

---

**Ready to deploy? Follow the steps above and you'll be up and running in minutes!**

**Good luck with your course applications! 🚀**
