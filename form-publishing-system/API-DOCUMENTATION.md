# Form Publishing System - API Documentation

Complete REST API reference for the Form Publishing System.

## Base URL

```
/wp-json/form-publishing/v1
```

## Authentication

All endpoints (except public ones) require WordPress REST API authentication. Include the `X-WP-Nonce` header with a valid WordPress nonce.

### Getting a Nonce

#### In PHP
```php
$nonce = wp_create_nonce('wp_rest');
```

#### In JavaScript (using wp_localize_script)
```javascript
wp_localize_script('my-script', 'apiData', array(
    'nonce' => wp_create_nonce('wp_rest')
));

// Then in your JS:
const nonce = apiData.nonce;
```

### Making Authenticated Requests

```javascript
fetch('/wp-json/form-publishing/v1/forms/1', {
    headers: {
        'X-WP-Nonce': nonce
    }
});
```

## Endpoints

### 1. Get All Forms

Retrieve a paginated list of all forms.

**Endpoint:** `GET /forms`

**Permissions:** `edit_posts`

**Query Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| status | string | - | Filter by status (draft, published, unpublished, scheduled) |
| page | integer | 1 | Page number |
| per_page | integer | 20 | Items per page (max 100) |

**Example Request:**
```bash
curl -X GET \
  'https://example.com/wp-json/form-publishing/v1/forms?status=published&page=1&per_page=10' \
  -H 'X-WP-Nonce: abc123'
```

**Example Response:**
```json
{
  "forms": [
    {
      "id": "1",
      "title": "Training Application Form",
      "description": "Application form for our comprehensive training program.",
      "status": "published",
      "created_at": "2026-01-01 10:00:00",
      "published_date": "2026-01-02 14:00:00",
      "view_count": "152",
      "submission_count": "43"
    }
  ],
  "total": 1,
  "page": 1,
  "per_page": 10,
  "total_pages": 1
}
```

**Status Codes:**
- `200`: Success
- `401`: Unauthorized
- `403`: Forbidden

---

### 2. Get Single Form

Retrieve details of a specific form.

**Endpoint:** `GET /forms/{id}`

**Permissions:** `edit_posts`

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | Form ID |

**Example Request:**
```bash
curl -X GET \
  'https://example.com/wp-json/form-publishing/v1/forms/1' \
  -H 'X-WP-Nonce: abc123'
```

**Example Response:**
```json
{
  "id": "1",
  "title": "Training Application Form",
  "description": "Application form for our comprehensive training program.",
  "form_fields": "[{\"type\":\"text\",\"label\":\"Full Name\",\"name\":\"full_name\",\"required\":true}]",
  "status": "published",
  "created_by": "1",
  "created_at": "2026-01-01 10:00:00",
  "updated_at": "2026-01-02 14:00:00",
  "published_by": "1",
  "published_date": "2026-01-02 14:00:00",
  "view_count": "152",
  "submission_count": "43"
}
```

**Status Codes:**
- `200`: Success
- `404`: Form not found
- `401`: Unauthorized

---

### 3. Publish Form

Publish a form, making it live and accessible.

**Endpoint:** `POST /forms/{id}/publish`

**Permissions:** `publish_posts` or `manage_options`

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | Form ID |

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| publish_date | string (datetime) | No | Specific publish date (format: YYYY-MM-DD HH:MM:SS) |

**Example Request:**
```bash
curl -X POST \
  'https://example.com/wp-json/form-publishing/v1/forms/1/publish' \
  -H 'Content-Type: application/json' \
  -H 'X-WP-Nonce: abc123' \
  -d '{
    "publish_date": "2026-01-15 10:00:00"
  }'
```

**Example Response:**
```json
{
  "success": true,
  "message": "Form published successfully",
  "form": {
    "id": "1",
    "title": "Training Application Form",
    "status": "published",
    "published_date": "2026-01-15 10:00:00"
  }
}
```

**Error Response:**
```json
{
  "error": "already_published",
  "message": "Form is already published"
}
```

**Status Codes:**
- `200`: Success
- `400`: Validation error
- `404`: Form not found
- `401`: Unauthorized
- `403`: Insufficient permissions

**Possible Errors:**
- `invalid_form`: Form not found
- `missing_title`: Form must have a title
- `missing_fields`: Form must have at least one field
- `already_published`: Form is already published

---

### 4. Unpublish Form

Unpublish a form, taking it offline.

**Endpoint:** `POST /forms/{id}/unpublish`

**Permissions:** `publish_posts` or `manage_options`

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | Form ID |

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| reason | string | No | Reason for unpublishing |

**Example Request:**
```bash
curl -X POST \
  'https://example.com/wp-json/form-publishing/v1/forms/1/unpublish' \
  -H 'Content-Type: application/json' \
  -H 'X-WP-Nonce: abc123' \
  -d '{
    "reason": "Form needs updates"
  }'
```

**Example Response:**
```json
{
  "success": true,
  "message": "Form unpublished successfully",
  "form": {
    "id": "1",
    "title": "Training Application Form",
    "status": "unpublished",
    "unpublished_date": "2026-01-03 09:30:00",
    "unpublish_reason": "Form needs updates"
  }
}
```

**Status Codes:**
- `200`: Success
- `400`: Validation error
- `404`: Form not found
- `401`: Unauthorized

**Possible Errors:**
- `invalid_form`: Form not found
- `not_published`: Form is not currently published

---

### 5. Schedule Form

Schedule a form for automatic publishing at a future date.

**Endpoint:** `POST /forms/{id}/schedule`

**Permissions:** `publish_posts` or `manage_options`

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | Form ID |

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| scheduled_date | string (datetime) | Yes | When to publish (format: YYYY-MM-DD HH:MM:SS) |

**Example Request:**
```bash
curl -X POST \
  'https://example.com/wp-json/form-publishing/v1/forms/1/schedule' \
  -H 'Content-Type: application/json' \
  -H 'X-WP-Nonce: abc123' \
  -d '{
    "scheduled_date": "2026-02-01 09:00:00"
  }'
```

**Example Response:**
```json
{
  "success": true,
  "message": "Form scheduled successfully",
  "form": {
    "id": "1",
    "title": "Training Application Form",
    "status": "scheduled",
    "scheduled_publish_date": "2026-02-01 09:00:00"
  }
}
```

**Status Codes:**
- `200`: Success
- `400`: Validation error
- `404`: Form not found
- `401`: Unauthorized

**Possible Errors:**
- `invalid_form`: Form not found
- `invalid_date`: Scheduled date must be in the future

---

### 6. Get Published Forms

Retrieve all currently published forms. This is a public endpoint.

**Endpoint:** `GET /forms/published`

**Permissions:** Public (no authentication required)

**Example Request:**
```bash
curl -X GET \
  'https://example.com/wp-json/form-publishing/v1/forms/published'
```

**Example Response:**
```json
{
  "forms": [
    {
      "id": "1",
      "title": "Training Application Form",
      "description": "Application form for our comprehensive training program.",
      "published_date": "2026-01-02 14:00:00",
      "view_count": "152",
      "submission_count": "43"
    }
  ],
  "count": 1
}
```

**Status Codes:**
- `200`: Success

---

### 7. Get Publishing History

Retrieve the complete publishing history for a form.

**Endpoint:** `GET /forms/{id}/history`

**Permissions:** `edit_posts`

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | Form ID |

**Example Request:**
```bash
curl -X GET \
  'https://example.com/wp-json/form-publishing/v1/forms/1/history' \
  -H 'X-WP-Nonce: abc123'
```

**Example Response:**
```json
{
  "history": [
    {
      "id": "3",
      "form_id": "1",
      "user_id": "1",
      "action": "unpublished",
      "previous_status": "published",
      "notes": "Form needs updates",
      "action_date": "2026-01-03 09:30:00"
    },
    {
      "id": "2",
      "form_id": "1",
      "user_id": "1",
      "action": "published",
      "previous_status": "draft",
      "notes": "",
      "action_date": "2026-01-02 14:00:00"
    },
    {
      "id": "1",
      "form_id": "1",
      "user_id": "1",
      "action": "draft",
      "previous_status": "",
      "notes": "Form created",
      "action_date": "2026-01-01 10:00:00"
    }
  ],
  "count": 3
}
```

**Status Codes:**
- `200`: Success
- `404`: Form not found
- `401`: Unauthorized

---

## Error Handling

### Error Response Format

All errors follow this format:

```json
{
  "error": "error_code",
  "message": "Human-readable error message"
}
```

### Common Error Codes

| Code | Description | HTTP Status |
|------|-------------|-------------|
| `invalid_form` | Form not found | 400 |
| `missing_title` | Form must have a title | 400 |
| `missing_fields` | Form must have fields | 400 |
| `already_published` | Form is already published | 400 |
| `not_published` | Form is not published | 400 |
| `invalid_date` | Invalid or past date | 400 |
| `publish_failed` | Database error | 400 |
| `unauthorized` | Missing or invalid nonce | 401 |
| `forbidden` | Insufficient permissions | 403 |

## Rate Limiting

Currently, there is no rate limiting implemented. This follows standard WordPress REST API behavior, which relies on server-level rate limiting.

## Pagination

For endpoints that return multiple items, pagination is handled via query parameters:

- `page`: Current page number (starts at 1)
- `per_page`: Items per page (max 100)

The response includes pagination metadata:
```json
{
  "total": 50,
  "page": 2,
  "per_page": 20,
  "total_pages": 3
}
```

## Filtering

### By Status
```
GET /forms?status=published
```

Valid statuses:
- `draft`
- `published`
- `unpublished`
- `scheduled`

## Complete Example: Publishing Workflow

```javascript
// 1. Get all draft forms
const drafts = await fetch('/wp-json/form-publishing/v1/forms?status=draft', {
  headers: { 'X-WP-Nonce': nonce }
}).then(r => r.json());

// 2. Publish the first draft
const formId = drafts.forms[0].id;
const publishResult = await fetch(`/wp-json/form-publishing/v1/forms/${formId}/publish`, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': nonce
  }
}).then(r => r.json());

console.log('Published:', publishResult.form.title);

// 3. Check publishing history
const history = await fetch(`/wp-json/form-publishing/v1/forms/${formId}/history`, {
  headers: { 'X-WP-Nonce': nonce }
}).then(r => r.json());

console.log('History:', history.history);

// 4. Get all published forms (public endpoint)
const published = await fetch('/wp-json/form-publishing/v1/forms/published')
  .then(r => r.json());

console.log('All published forms:', published.forms);
```

## Webhook Integration (Future)

While not currently implemented, you can use WordPress action hooks to create webhooks:

```php
add_action('training_form_published', function($form_id, $user_id) {
    wp_remote_post('https://your-webhook-url.com', array(
        'body' => json_encode(array(
            'event' => 'form_published',
            'form_id' => $form_id,
            'user_id' => $user_id
        ))
    ));
}, 10, 2);
```

## Testing

### Using cURL

```bash
# Set your site URL and nonce
SITE_URL="https://example.com"
NONCE="your-nonce-here"

# Get all forms
curl -X GET "${SITE_URL}/wp-json/form-publishing/v1/forms" \
  -H "X-WP-Nonce: ${NONCE}"

# Publish a form
curl -X POST "${SITE_URL}/wp-json/form-publishing/v1/forms/1/publish" \
  -H "Content-Type: application/json" \
  -H "X-WP-Nonce: ${NONCE}"
```

### Using Postman

1. Set `X-WP-Nonce` header with a valid nonce
2. Use the endpoints as documented
3. For POST requests, set Content-Type to `application/json`

## Support

For API questions or issues:
- GitHub Issues: https://github.com/SPARKCDB/TrainingForm/issues
- Documentation: See README.md

## Version History

- **v1.0.0** (2026-01-02): Initial API release
