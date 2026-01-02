# Installation Guide - Form Publishing System

Complete installation instructions for the Form Publishing System WordPress plugin.

## Table of Contents
- [Requirements](#requirements)
- [Installation Methods](#installation-methods)
- [Post-Installation Setup](#post-installation-setup)
- [Verification](#verification)
- [Troubleshooting](#troubleshooting)
- [Upgrading](#upgrading)
- [Uninstallation](#uninstallation)

## Requirements

### System Requirements

| Component | Minimum | Recommended |
|-----------|---------|-------------|
| WordPress | 5.8 | Latest stable |
| PHP | 7.4 | 8.0 or higher |
| MySQL | 5.6 | 8.0 or higher |
| Memory | 64MB | 128MB or higher |

### PHP Extensions
- `json` - Required for form field storage
- `mysqli` or `pdo_mysql` - Database connection
- `mbstring` - String handling

### WordPress Capabilities
The logged-in user needs:
- `edit_posts` - To view forms
- `publish_posts` - To publish/unpublish forms
- `manage_options` - Admin access (optional, but recommended)

## Installation Methods

### Method 1: Manual Installation (Recommended)

#### Step 1: Download
Download or clone the plugin files to your local machine.

#### Step 2: Upload
1. Connect to your WordPress server via FTP or file manager
2. Navigate to `/wp-content/plugins/`
3. Create a new folder named `form-publishing-system`
4. Upload all plugin files to this folder

Your directory structure should look like:
```
/wp-content/plugins/form-publishing-system/
├── assets/
│   ├── css/
│   │   ├── admin.css
│   │   └── index.php
│   ├── js/
│   │   ├── admin.js
│   │   └── index.php
│   └── index.php
├── includes/
│   ├── class-admin-interface.php
│   ├── class-database.php
│   ├── class-form-publisher.php
│   ├── class-rest-api.php
│   └── index.php
├── form-publishing-system.php
├── index.php
├── uninstall.php
├── LICENSE.txt
├── README.md
├── INSTALLATION.md
├── QUICK-START.md
└── API-DOCUMENTATION.md
```

#### Step 3: Activate
1. Log in to your WordPress admin panel
2. Navigate to **Plugins** → **Installed Plugins**
3. Find "Form Publishing System"
4. Click **Activate**

### Method 2: WordPress Admin Upload

#### Step 1: Create ZIP
Create a ZIP file of the plugin directory:
```bash
cd /path/to/plugin
zip -r form-publishing-system.zip form-publishing-system/
```

#### Step 2: Upload via Admin
1. Log in to WordPress admin
2. Navigate to **Plugins** → **Add New**
3. Click **Upload Plugin**
4. Choose the ZIP file
5. Click **Install Now**
6. Click **Activate Plugin**

### Method 3: WP-CLI Installation

If you have WP-CLI installed:

```bash
# Navigate to WordPress root
cd /path/to/wordpress

# Create plugin directory
wp plugin install --activate form-publishing-system.zip

# Or if you have the directory:
cd wp-content/plugins/
ln -s /path/to/form-publishing-system form-publishing-system
wp plugin activate form-publishing-system
```

### Method 4: Composer (Advanced)

If using Composer for WordPress plugin management:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/SPARKCDB/TrainingForm"
    }
  ],
  "require": {
    "sparkcdb/form-publishing-system": "^1.0"
  }
}
```

Then run:
```bash
composer install
```

## Post-Installation Setup

### Step 1: Database Initialization

The plugin automatically creates database tables on activation:
- `wp_training_forms`
- `wp_training_forms_publish_history`

To verify tables were created:

```sql
SHOW TABLES LIKE 'wp_training_forms%';
```

Or using WP-CLI:
```bash
wp db query "SHOW TABLES LIKE 'wp_training_forms%'"
```

### Step 2: Verify Cron Job

The plugin schedules a cron job for processing scheduled forms.

Check if it's registered:
```bash
wp cron event list
```

You should see: `form_publishing_cron` scheduled hourly.

### Step 3: Check Permissions

Verify that the correct user roles have the necessary capabilities:

1. **Editors and Administrators** should be able to:
   - View forms
   - Publish/unpublish forms
   - Schedule forms
   - View history

2. **Other roles** (Authors, Contributors) may need custom capabilities added.

### Step 4: Sample Form

The plugin creates a sample "Training Application Form" automatically. To verify:

1. Navigate to **Form Publishing** → **All Forms**
2. You should see one sample form
3. Try publishing it to test functionality

## Verification

### Quick Verification Checklist

✅ Plugin appears in WordPress admin menu  
✅ Database tables created  
✅ Sample form exists  
✅ Can view forms page  
✅ Can publish/unpublish forms  
✅ REST API endpoints respond  
✅ Cron job scheduled  

### Testing REST API

Test if the API is working:

```bash
# Get published forms (public endpoint)
curl https://your-site.com/wp-json/form-publishing/v1/forms/published

# Should return JSON with forms array
```

### Testing Admin Interface

1. Navigate to **Form Publishing** in admin menu
2. You should see:
   - Statistics cards at the top
   - Forms table
   - Action buttons (Publish, History, etc.)

### Testing Publishing

1. Find the sample form (status: Draft)
2. Click **Publish**
3. Verify status changes to "Published"
4. Click **History** to see the action logged

## Troubleshooting

### Plugin Not Appearing in Menu

**Problem:** "Form Publishing" menu item not visible.

**Solutions:**
1. Check user has `edit_posts` capability
2. Clear browser cache
3. Deactivate and reactivate plugin
4. Check for JavaScript errors in browser console

### Database Tables Not Created

**Problem:** Tables `wp_training_forms` or `wp_training_forms_publish_history` don't exist.

**Solutions:**
1. Deactivate and reactivate plugin
2. Check database user permissions (needs CREATE TABLE)
3. Manually run table creation:
```php
require_once('/path/to/wordpress/wp-load.php');
require_once('/path/to/plugin/includes/class-database.php');
Form_Publishing_Database::create_tables();
```

### REST API Not Working

**Problem:** API endpoints return 404 or errors.

**Solutions:**
1. Flush rewrite rules:
   - Go to **Settings** → **Permalinks**
   - Click **Save Changes** (no need to change anything)
2. Or via WP-CLI:
   ```bash
   wp rewrite flush
   ```
3. Verify REST API is enabled:
   ```bash
   curl https://your-site.com/wp-json/
   ```

### Cron Not Processing Scheduled Forms

**Problem:** Scheduled forms don't publish automatically.

**Solutions:**
1. Check if WordPress cron is working:
   ```bash
   wp cron test
   ```
2. Manually trigger cron:
   ```bash
   wp cron event run form_publishing_cron
   ```
3. If using server cron instead of WP-Cron, add to crontab:
   ```bash
   0 * * * * cd /path/to/wordpress && wp cron event run form_publishing_cron
   ```

### Permission Errors

**Problem:** "You don't have permission to publish forms."

**Solutions:**
1. Check user has `publish_posts` or `manage_options` capability
2. Add capability to role:
```php
$role = get_role('editor');
$role->add_cap('publish_posts');
```

### Assets Not Loading

**Problem:** Admin CSS/JS not loading properly.

**Solutions:**
1. Clear WordPress cache
2. Check file permissions (should be 644 for files, 755 for directories)
3. Verify asset URLs in browser console
4. Regenerate asset files

## Upgrading

### From Future Versions

When upgrading to a newer version:

1. **Backup Database**
   ```bash
   wp db export backup.sql
   ```

2. **Backup Plugin Files**
   ```bash
   cp -r wp-content/plugins/form-publishing-system wp-content/plugins/form-publishing-system.backup
   ```

3. **Deactivate Plugin**
   - Navigate to **Plugins** → **Installed Plugins**
   - Click **Deactivate** for Form Publishing System

4. **Replace Files**
   - Upload new plugin files
   - Overwrite existing files

5. **Reactivate Plugin**
   - Click **Activate**
   - Plugin will run any necessary database migrations

6. **Verify**
   - Check forms still exist
   - Test publishing functionality
   - Check API endpoints

### Database Migrations

Version 1.0.0 is the initial release. Future versions with database changes will automatically run migrations on activation.

## Uninstallation

### Complete Removal

To completely remove the plugin and all data:

1. **Deactivate Plugin**
   - Navigate to **Plugins** → **Installed Plugins**
   - Click **Deactivate**

2. **Delete Plugin**
   - Click **Delete**
   - Confirm deletion

The uninstall script (`uninstall.php`) will automatically:
- Drop database tables
- Delete plugin options
- Clear scheduled cron jobs

### Keeping Data

If you want to keep form data when uninstalling:

```php
// Add this before uninstalling
update_option('form_publishing_keep_data', true);
```

Then uninstall normally. Data will be preserved.

### Manual Cleanup

If needed, manually remove data:

```sql
-- Drop tables
DROP TABLE IF EXISTS wp_training_forms_publish_history;
DROP TABLE IF EXISTS wp_training_forms;

-- Delete options
DELETE FROM wp_options WHERE option_name LIKE 'form_publishing%';
```

And clear cron:
```bash
wp cron event delete form_publishing_cron
```

## Advanced Configuration

### Custom Database Prefix

If your WordPress uses a custom database prefix, the plugin automatically detects it via `$wpdb->prefix`. No configuration needed.

### Multisite Installation

For WordPress Multisite:

1. **Network Activate**
   - Navigate to **Network Admin** → **Plugins**
   - Click **Network Activate** for Form Publishing System

2. **Per-Site Tables**
   - Each site gets its own tables: `wp_2_training_forms`, `wp_3_training_forms`, etc.

3. **Permissions**
   - Super Admins can manage all forms
   - Site Admins manage forms on their site only

### Custom Memory Limits

If you have many forms, increase PHP memory:

```php
// In wp-config.php
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');
```

### Debug Mode

Enable debugging to troubleshoot issues:

```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Check logs at: `/wp-content/debug.log`

## Support

### Getting Help

- **Documentation**: See README.md and API-DOCUMENTATION.md
- **Quick Start**: See QUICK-START.md
- **Issues**: https://github.com/SPARKCDB/TrainingForm/issues
- **Discussions**: https://github.com/SPARKCDB/TrainingForm/discussions

### Before Seeking Support

Please provide:
1. WordPress version
2. PHP version
3. Plugin version
4. Error messages (from debug.log)
5. Steps to reproduce the issue

## Success!

If you've completed all steps successfully, you should now have:
- ✅ Form Publishing System installed and activated
- ✅ Database tables created
- ✅ Sample form available
- ✅ Admin interface accessible
- ✅ REST API working
- ✅ Cron jobs scheduled

**Next Steps:** See [QUICK-START.md](QUICK-START.md) to start using the plugin!
