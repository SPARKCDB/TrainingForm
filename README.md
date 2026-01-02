# TrainingForm - Form Publishing System

A comprehensive WordPress plugin for managing training application forms with advanced publishing capabilities including status management, scheduling, and history tracking.

## Features

### 🚀 Core Functionality
- **Form Publishing Management**: Publish, unpublish, and schedule forms with ease
- **Status Tracking**: Track form status (Draft, Published, Unpublished, Scheduled)
- **Publishing History**: Complete audit trail of all publishing actions
- **Automated Scheduling**: Automatically publish forms at scheduled times
- **REST API**: Full REST API for programmatic access
- **Admin Dashboard**: Beautiful, intuitive admin interface

### 📊 Form Management
- Create and manage multiple training forms
- Track form views and submissions
- View detailed publishing history for each form
- Bulk actions support

### 🔒 Security & Permissions
- WordPress capability-based permissions
- Nonce verification for all actions
- Secure REST API endpoints
- User action tracking

### ⏰ Scheduling
- Schedule forms for future publishing
- Automated cron processing
- Timezone-aware scheduling
- Cancel or reschedule anytime

## Installation

### Manual Installation

1. Download or clone this repository
2. Upload the `form-publishing-system` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to "Form Publishing" in the admin menu

### Via Composer (if configured)

```bash
composer install
```

### Database Setup

The plugin automatically creates the required database tables on activation:
- `wp_training_forms` - Stores form data and status
- `wp_training_forms_publish_history` - Tracks publishing history

## Usage

### Publishing a Form

#### Via Admin Interface

1. Navigate to **Form Publishing** → **All Forms**
2. Find the form you want to publish
3. Click the **Publish** button
4. The form status will change to "Published"

#### Via REST API

```bash
POST /wp-json/form-publishing/v1/forms/{id}/publish
Content-Type: application/json
X-WP-Nonce: {nonce}

{
  "publish_date": "2026-01-15 10:00:00" // Optional
}
```

### Unpublishing a Form

#### Via Admin Interface

1. Navigate to **Form Publishing** → **All Forms**
2. Find the published form
3. Click the **Unpublish** button
4. Optionally provide a reason for unpublishing

#### Via REST API

```bash
POST /wp-json/form-publishing/v1/forms/{id}/unpublish
Content-Type: application/json
X-WP-Nonce: {nonce}

{
  "reason": "Form needs updates" // Optional
}
```

### Scheduling a Form

#### Via Admin Interface

1. Navigate to **Form Publishing** → **All Forms**
2. Find the form you want to schedule
3. Click **Actions** → **Schedule**
4. Select the publish date and time
5. Click **Schedule**

#### Via REST API

```bash
POST /wp-json/form-publishing/v1/forms/{id}/schedule
Content-Type: application/json
X-WP-Nonce: {nonce}

{
  "scheduled_date": "2026-01-20 14:00:00"
}
```

### Viewing Publishing History

#### Via Admin Interface

1. Navigate to **Form Publishing** → **All Forms**
2. Click **History** next to any form
3. View the complete publishing timeline

#### Via REST API

```bash
GET /wp-json/form-publishing/v1/forms/{id}/history
X-WP-Nonce: {nonce}
```

## REST API Endpoints

### Authentication

All endpoints require WordPress REST API authentication. Include the `X-WP-Nonce` header with a valid nonce.

### Endpoints

#### Get All Forms
```
GET /wp-json/form-publishing/v1/forms
Query Parameters:
  - status: Filter by status (draft, published, unpublished, scheduled)
  - page: Page number (default: 1)
  - per_page: Items per page (default: 20)
```

#### Get Single Form
```
GET /wp-json/form-publishing/v1/forms/{id}
```

#### Publish Form
```
POST /wp-json/form-publishing/v1/forms/{id}/publish
Body: { "publish_date": "YYYY-MM-DD HH:MM:SS" } // Optional
```

#### Unpublish Form
```
POST /wp-json/form-publishing/v1/forms/{id}/unpublish
Body: { "reason": "string" } // Optional
```

#### Schedule Form
```
POST /wp-json/form-publishing/v1/forms/{id}/schedule
Body: { "scheduled_date": "YYYY-MM-DD HH:MM:SS" } // Required
```

#### Get Published Forms
```
GET /wp-json/form-publishing/v1/forms/published
```

#### Get Publishing History
```
GET /wp-json/form-publishing/v1/forms/{id}/history
```

## Database Schema

### `wp_training_forms` Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) | Primary key |
| title | varchar(255) | Form title |
| description | text | Form description |
| form_fields | longtext | JSON-encoded form fields |
| status | varchar(20) | Current status |
| created_by | bigint(20) | Creator user ID |
| created_at | datetime | Creation timestamp |
| updated_at | datetime | Last update timestamp |
| published_by | bigint(20) | Publisher user ID |
| published_date | datetime | Publish timestamp |
| unpublished_by | bigint(20) | Unpublisher user ID |
| unpublished_date | datetime | Unpublish timestamp |
| unpublish_reason | text | Reason for unpublishing |
| scheduled_by | bigint(20) | Scheduler user ID |
| scheduled_publish_date | datetime | Scheduled publish time |
| view_count | bigint(20) | Number of views |
| submission_count | bigint(20) | Number of submissions |

### `wp_training_forms_publish_history` Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) | Primary key |
| form_id | bigint(20) | Related form ID |
| user_id | bigint(20) | User who performed action |
| action | varchar(50) | Action performed |
| previous_status | varchar(20) | Previous form status |
| notes | text | Additional notes |
| action_date | datetime | Action timestamp |

## WordPress Hooks

### Actions

#### `training_form_published`
Fires when a form is published.

```php
do_action('training_form_published', $form_id, $user_id);
```

#### `training_form_unpublished`
Fires when a form is unpublished.

```php
do_action('training_form_unpublished', $form_id, $user_id, $reason);
```

#### `training_form_scheduled`
Fires when a form is scheduled.

```php
do_action('training_form_scheduled', $form_id, $user_id, $scheduled_date);
```

### Filters

#### `training_form_validate_for_publishing`
Filter form validation before publishing.

```php
$is_valid = apply_filters('training_form_validate_for_publishing', true, $form_id, $form);
```

## Customization

### Adding Custom Validation

```php
add_filter('training_form_validate_for_publishing', function($valid, $form_id, $form) {
    // Your custom validation logic
    if (empty($form['custom_field'])) {
        return new WP_Error('missing_custom_field', 'Custom field is required');
    }
    return $valid;
}, 10, 3);
```

### Custom Actions on Publishing

```php
add_action('training_form_published', function($form_id, $user_id) {
    // Send notification email
    // Update external systems
    // Log to analytics
}, 10, 2);
```

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher

## Permissions

- **View Forms**: `edit_posts` capability
- **Publish/Unpublish Forms**: `publish_posts` or `manage_options` capability

## Cron Jobs

The plugin registers a cron job that runs hourly to process scheduled forms:

```php
wp_schedule_event(time(), 'hourly', 'form_publishing_cron');
```

To manually trigger scheduled form processing:

```bash
wp cron event run form_publishing_cron
```

## Troubleshooting

### Forms Not Publishing

1. Check user permissions
2. Verify form has required fields (title, form_fields)
3. Check error logs for validation errors

### Scheduled Forms Not Publishing

1. Verify WordPress cron is working: `wp cron test`
2. Check scheduled date is in the past
3. Manually trigger cron: `wp cron event run form_publishing_cron`

### REST API Not Working

1. Verify permalinks are enabled
2. Check REST API is accessible: `/wp-json/`
3. Ensure proper nonce is being sent

## Development

### File Structure

```
form-publishing-system/
├── assets/
│   ├── css/
│   │   └── admin.css          # Admin interface styles
│   └── js/
│       └── admin.js           # Admin interface JavaScript
├── includes/
│   ├── class-admin-interface.php   # Admin dashboard
│   ├── class-database.php          # Database management
│   ├── class-form-publisher.php    # Core publishing logic
│   └── class-rest-api.php          # REST API endpoints
└── form-publishing-system.php      # Main plugin file
```

### Coding Standards

This plugin follows WordPress Coding Standards:
- [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- [JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)
- [CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/)

## License

This plugin is licensed under the GPL v2 or later.

## Support

For issues, questions, or contributions, please visit:
- GitHub: https://github.com/SPARKCDB/TrainingForm
- Issues: https://github.com/SPARKCDB/TrainingForm/issues

## Changelog

### Version 1.0.0 (2026-01-02)
- Initial release
- Form publishing/unpublishing functionality
- Scheduling support
- Publishing history tracking
- REST API endpoints
- Admin dashboard interface
- Automated cron processing
- Sample form generation

## Credits

Developed by SPARKCDB

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request
