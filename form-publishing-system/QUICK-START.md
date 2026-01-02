# Form Publishing System - Quick Start Guide

Get up and running with the Form Publishing System in minutes!

## Installation

### Step 1: Install the Plugin

1. Upload the `form-publishing-system` folder to `/wp-content/plugins/`
2. Navigate to **Plugins** in your WordPress admin
3. Find "Form Publishing System" and click **Activate**

### Step 2: Verify Installation

After activation, you should see:
- ✅ A new "Form Publishing" menu item in the admin sidebar
- ✅ Database tables created automatically
- ✅ A sample form created for testing

## First Steps

### Viewing Your Forms

1. Click **Form Publishing** → **All Forms** in the admin menu
2. You'll see a dashboard with:
   - Form statistics (Total, Published, Drafts, Scheduled)
   - A table listing all forms
   - Action buttons for each form

### Publishing Your First Form

The plugin creates a sample "Training Application Form" automatically. Let's publish it:

1. Find the "Training Application Form" in the forms table
2. Click the **Publish** button
3. The form status will change to "Published" 🎉
4. The form is now live and visible to the public!

### Viewing Publishing History

To see what happened to a form:

1. Click **History** next to any form
2. A modal will show you:
   - When the form was published
   - Who published it
   - Any notes or reasons for actions

### Unpublishing a Form

Need to take a form offline?

1. Find a published form
2. Click **Unpublish**
3. Optionally enter a reason (e.g., "Needs updates")
4. The form is now unpublished

### Scheduling a Form

Want to publish a form at a specific time?

1. Click **Actions** → **Schedule** for any form
2. Select a date and time in the future
3. Click **Schedule**
4. The form will automatically publish at the scheduled time

## Understanding Form Statuses

| Status | Description | Color |
|--------|-------------|-------|
| **Draft** | Form is not published | Gray |
| **Published** | Form is live and public | Green |
| **Unpublished** | Form was published but taken offline | Red |
| **Scheduled** | Form will publish at a future date | Blue |

## Dashboard Overview

### Statistics Cards

At the top of the dashboard, you'll see four cards:
- **Total Forms**: All forms in the system
- **Published**: Forms currently live
- **Drafts**: Forms not yet published
- **Scheduled**: Forms set to publish later

### Forms Table

The table shows:
- **ID**: Unique form identifier
- **Title**: Form name
- **Status**: Current publishing status
- **Created**: When the form was created
- **Published Date**: When the form was published (if applicable)
- **Views**: Number of times the form was viewed
- **Submissions**: Number of form submissions
- **Actions**: Buttons to manage the form

## Using the REST API

### Getting Your Nonce

In JavaScript:
```javascript
const nonce = document.querySelector('#wpapi-nonce')?.getAttribute('content');
// or if using wp_localize_script
const nonce = myPluginData.nonce;
```

### Publishing a Form via API

```javascript
fetch('/wp-json/form-publishing/v1/forms/1/publish', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': nonce
  }
})
.then(response => response.json())
.then(data => console.log('Form published!', data));
```

### Getting All Published Forms

```javascript
fetch('/wp-json/form-publishing/v1/forms/published')
  .then(response => response.json())
  .then(data => console.log('Published forms:', data.forms));
```

## Common Tasks

### Task 1: Bulk Publishing Forms

Currently, forms are published individually. For bulk operations, use the REST API in a loop:

```javascript
const formIds = [1, 2, 3, 4, 5];

formIds.forEach(async (id) => {
  await fetch(`/wp-json/form-publishing/v1/forms/${id}/publish`, {
    method: 'POST',
    headers: { 'X-WP-Nonce': nonce }
  });
});
```

### Task 2: Scheduling Multiple Forms

To schedule several forms for the same time:

```javascript
const formIds = [1, 2, 3];
const scheduledDate = '2026-02-01 09:00:00';

formIds.forEach(async (id) => {
  await fetch(`/wp-json/form-publishing/v1/forms/${id}/schedule`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-WP-Nonce': nonce
    },
    body: JSON.stringify({ scheduled_date: scheduledDate })
  });
});
```

### Task 3: Monitoring Form Status

Get all forms and their statuses:

```javascript
fetch('/wp-json/form-publishing/v1/forms')
  .then(response => response.json())
  .then(data => {
    data.forms.forEach(form => {
      console.log(`${form.title}: ${form.status}`);
    });
  });
```

## Automation

### Automatic Scheduled Publishing

The plugin automatically processes scheduled forms every hour via WordPress cron.

To manually trigger processing:
```bash
wp cron event run form_publishing_cron
```

To check scheduled events:
```bash
wp cron event list
```

### Custom Automation

Hook into form publishing events:

```php
// Send email when form is published
add_action('training_form_published', function($form_id, $user_id) {
    $form = (new Form_Publisher())->get_form($form_id);
    
    wp_mail(
        get_option('admin_email'),
        'Form Published: ' . $form['title'],
        'The form "' . $form['title'] . '" has been published.'
    );
}, 10, 2);
```

## Tips & Best Practices

### 1. Test Before Publishing
Always review forms in draft status before publishing to ensure they're ready.

### 2. Use Scheduling for Campaigns
Schedule forms to publish at the start of registration periods.

### 3. Track Publishing History
Use the history feature to understand form lifecycle and troubleshoot issues.

### 4. Monitor Statistics
Keep an eye on view counts and submissions to gauge form performance.

### 5. Document Unpublish Reasons
Always provide a reason when unpublishing forms for better team communication.

## Next Steps

Now that you're familiar with the basics:

1. **Explore the REST API**: Build custom integrations
2. **Customize with Hooks**: Add custom logic to publishing events
3. **Create More Forms**: Build forms for different purposes
4. **Set Up Monitoring**: Track form performance and submissions

## Need Help?

- 📖 Full documentation: See README.md
- 🐛 Report issues: GitHub Issues
- 💡 Feature requests: GitHub Discussions

## Quick Reference Card

```
┌─────────────────────────────────────┐
│  FORM PUBLISHING QUICK REFERENCE    │
├─────────────────────────────────────┤
│ Admin Menu: Form Publishing         │
│ REST API: /form-publishing/v1       │
│ Cron: form_publishing_cron          │
│                                     │
│ PERMISSIONS:                        │
│ • View: edit_posts                  │
│ • Publish: publish_posts            │
│                                     │
│ STATUSES:                           │
│ • draft → published                 │
│ • published → unpublished           │
│ • draft → scheduled → published     │
└─────────────────────────────────────┘
```

Happy publishing! 🚀
