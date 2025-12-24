# Plugin Screenshots & UI Reference

This document describes what each interface looks like for documentation purposes.

## Frontend Form

### Application Form Page

**Location:** Any page with `[application_form]` shortcode

**Layout:**
```
╔════════════════════════════════════════════════════════════╗
║                  Course Application Form                   ║
╠════════════════════════════════════════════════════════════╣
║                                                            ║
║  PERSONAL INFORMATION                                      ║
║  ───────────────────────────────────────────────────      ║
║                                                            ║
║  Full Name *                                               ║
║  [___________________________________________]             ║
║                                                            ║
║  NRIC No *                    Contact No *                 ║
║  [__________________]         [__________________]         ║
║                                                            ║
║  Email Address *                                           ║
║  [___________________________________________]             ║
║                                                            ║
║  Home Address *                                            ║
║  [___________________________________________]             ║
║  [___________________________________________]             ║
║  [___________________________________________]             ║
║                                                            ║
║  Course Interested *                                       ║
║  [Select a course ▼                          ]             ║
║                                                            ║
║  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━            ║
║                                                            ║
║  APPLICATION DOCUMENTS                                     ║
║  ───────────────────────────────────────────────────      ║
║  Please upload certified true copies (Salinan Diakui Sah)║
║                                                            ║
║  NRIC Front Copy *                                         ║
║  [📁 Choose File]                                          ║
║  Accepted formats: PDF, JPG, PNG (Max size: 5MB)          ║
║                                                            ║
║  NRIC Back Copy *                                          ║
║  [📁 Choose File]                                          ║
║  Accepted formats: PDF, JPG, PNG (Max size: 5MB)          ║
║                                                            ║
║  Birth Certificate Copy *                                  ║
║  [📁 Choose File]                                          ║
║  Accepted formats: PDF, JPG, PNG (Max size: 5MB)          ║
║                                                            ║
║  Bank Statement Front Page *                               ║
║  [📁 Choose File]                                          ║
║  Accepted formats: PDF, JPG, PNG (Max size: 5MB)          ║
║                                                            ║
║  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━            ║
║                                                            ║
║              [  Submit Application  ]                      ║
║                                                            ║
╚════════════════════════════════════════════════════════════╝
```

**Features:**
- Clean, modern design
- Clear section headers
- Required field indicators (*)
- Help text for file uploads
- Professional color scheme (blue #0073aa)
- Responsive layout
- Real-time validation

### Success Message

```
╔════════════════════════════════════════════════════════════╗
║  ✓ Your application has been submitted successfully!      ║
║    You will receive a confirmation email shortly.         ║
╚════════════════════════════════════════════════════════════╝
```

### Error Message

```
╔════════════════════════════════════════════════════════════╗
║  ✗ Please correct the following errors:                   ║
║    • Full Name is required                                ║
║    • Please enter a valid email address                   ║
╚════════════════════════════════════════════════════════════╝
```

---

## Admin Interface

### Applications List Page

**Location:** WordPress Admin → Application Forms

```
╔════════════════════════════════════════════════════════════════════════════════╗
║ Application Forms                                    [Export to CSV]           ║
╠════════════════════════════════════════════════════════════════════════════════╣
║                                                                                ║
║ All (45) | Pending (12) | Approved (28) | Rejected (5)                        ║
║                                                                                ║
║ [Search applications...          ] [Search]                                   ║
║                                                                                ║
╠════╤════════════════╤══════════════╤════════════════╤═══════╤════════╤════════╣
║ ID │ Full Name      │ Email        │ Contact        │ Status│ Date   │ Action ║
╠════╪════════════════╪══════════════╪════════════════╪═══════╪════════╪════════╣
║ 23 │ John Doe       │ john@ex.com  │ +60123456789  │ [PEN] │ Dec 24 │ [View] ║
║ 22 │ Jane Smith     │ jane@ex.com  │ +60198765432  │ [APP] │ Dec 23 │ [View] ║
║ 21 │ Bob Johnson    │ bob@ex.com   │ +60187654321  │ [REJ] │ Dec 22 │ [View] ║
╠════╧════════════════╧══════════════╧════════════════╧═══════╧════════╧════════╣
║                          « ‹ 1 2 3 › »                                        ║
╚════════════════════════════════════════════════════════════════════════════════╝

Legend:
[PEN] = Pending (Yellow badge)
[APP] = Approved (Green badge)
[REJ] = Rejected (Red badge)
[REV] = Under Review (Blue badge)
```

**Features:**
- Sortable columns
- Status filter tabs
- Search functionality
- Pagination
- Color-coded status badges
- Quick actions

### Single Application View

**Location:** Application Forms → Click "View"

```
╔════════════════════════════════════════════════════════════════════════════════╗
║ View Application #23                                     [← Back to List]     ║
╠════════════════════════════════════════════════════════════════════════════════╣
║                                                                                ║
║ PERSONAL INFORMATION                                                           ║
║ ──────────────────────────────────────────────────────────────────────        ║
║                                                                                ║
║ Full Name:         John Doe                                                   ║
║ NRIC No:           123456-12-1234                                             ║
║ Contact No:        +60123456789                                               ║
║ Email:             john.doe@example.com                                       ║
║ Home Address:      123 Main Street, City, State, 12345                        ║
║ Course Interested: Diploma in Information Technology                          ║
║                                                                                ║
║ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  ║
║                                                                                ║
║ APPLICATION DOCUMENTS                                                          ║
║ ──────────────────────────────────────────────────────────────────────        ║
║                                                                                ║
║ NRIC Front:         [View File]                                               ║
║ NRIC Back:          [View File]                                               ║
║ Birth Certificate:  [View File]                                               ║
║ Bank Statement:     [View File]                                               ║
║                                                                                ║
║ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  ║
║                                                                                ║
║ APPLICATION STATUS                                                             ║
║ ──────────────────────────────────────────────────────────────────────        ║
║                                                                                ║
║ Current Status:    [Pending ▼                    ]                            ║
║                                                                                ║
║ Notes:             [_________________________________]                         ║
║                    [_________________________________]                         ║
║                    [_________________________________]                         ║
║                                                                                ║
║                    [Update Status]                                            ║
║                                                                                ║
║ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  ║
║                                                                                ║
║ ADDITIONAL INFORMATION                                                         ║
║ ──────────────────────────────────────────────────────────────────────        ║
║                                                                                ║
║ Submitted Date:    December 24, 2025 at 10:30 AM                             ║
║ Last Updated:      December 24, 2025 at 10:30 AM                             ║
║ IP Address:        192.168.1.1                                               ║
║                                                                                ║
║ [Delete Application]                                                          ║
║                                                                                ║
╚════════════════════════════════════════════════════════════════════════════════╝
```

**Features:**
- Complete application details
- Downloadable documents
- Status update form
- Notes section
- Application metadata
- Delete option with confirmation

### Settings Page

**Location:** Application Forms → Settings

```
╔════════════════════════════════════════════════════════════════════════════════╗
║ Application Form Settings                                                     ║
╠════════════════════════════════════════════════════════════════════════════════╣
║                                                                                ║
║ Admin Email:       [admin@example.com                    ]                    ║
║                    Email address to receive new application notifications.    ║
║                                                                                ║
║ Email Notifications:  ☑ Enable email notifications for new applications      ║
║                                                                                ║
║ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  ║
║                                                                                ║
║ SHORTCODE USAGE                                                                ║
║ ──────────────────────────────────────────────────────────────────────        ║
║                                                                                ║
║ Use the following shortcode to display the application form:                  ║
║                                                                                ║
║   [application_form]                                                           ║
║                                                                                ║
║ You can also customize the title:                                             ║
║                                                                                ║
║   [application_form title="Custom Title Here"]                                ║
║                                                                                ║
║ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  ║
║                                                                                ║
║ [Save Settings]                                                                ║
║                                                                                ║
╚════════════════════════════════════════════════════════════════════════════════╝
```

---

## Email Templates

### Admin Notification Email

```
╔════════════════════════════════════════════════════════════╗
║                   New Application Received                  ║
╚════════════════════════════════════════════════════════════╝

A new course application has been submitted on Your Site Name.

APPLICANT INFORMATION
────────────────────────────────────────────────────────────

Full Name:         John Doe
NRIC No:           123456-12-1234
Contact No:        +60123456789
Email:             john.doe@example.com
Home Address:      123 Main Street, City, State, 12345
Course Interested: Diploma in Information Technology
Submitted Date:    December 24, 2025 at 10:30 AM

Documents uploaded:
• NRIC Front Copy: ✓ Uploaded
• NRIC Back Copy: ✓ Uploaded
• Birth Certificate: ✓ Uploaded
• Bank Statement: ✓ Uploaded

                    [View Full Application]

────────────────────────────────────────────────────────────
This is an automated notification from Your Site Name.
```

### Applicant Confirmation Email

```
╔════════════════════════════════════════════════════════════╗
║                   Application Received                      ║
╚════════════════════════════════════════════════════════════╝

Dear John Doe,

Thank you for submitting your course application to Your Site Name.
We have successfully received your application.

APPLICATION SUMMARY
────────────────────────────────────────────────────────────

Course:    Diploma in Information Technology
Submitted: December 24, 2025 at 10:30 AM

WHAT HAPPENS NEXT?

• Our admissions team will review your application and documents
• We will contact you within 3-5 business days
• You will be notified via email once your application is processed

If you have any questions, please don't hesitate to contact us.

Best regards,
Your Site Name

────────────────────────────────────────────────────────────
This is an automated confirmation email from Your Site Name.
https://yoursite.com
```

---

## Mobile View

### Form on Mobile (375px width)

```
┌────────────────────────────┐
│ Course Application Form    │
├────────────────────────────┤
│                            │
│ PERSONAL INFORMATION       │
│ ────────────────────       │
│                            │
│ Full Name *                │
│ [____________________]     │
│                            │
│ NRIC No *                  │
│ [____________________]     │
│                            │
│ Contact No *               │
│ [____________________]     │
│                            │
│ Email Address *            │
│ [____________________]     │
│                            │
│ Home Address *             │
│ [____________________]     │
│ [____________________]     │
│                            │
│ Course Interested *        │
│ [Select... ▼          ]    │
│                            │
│ ━━━━━━━━━━━━━━━━━━━━━━    │
│                            │
│ APPLICATION DOCUMENTS      │
│ ────────────────────       │
│                            │
│ NRIC Front Copy *          │
│ [📁 Choose File]           │
│                            │
│ NRIC Back Copy *           │
│ [📁 Choose File]           │
│                            │
│ Birth Certificate *        │
│ [📁 Choose File]           │
│                            │
│ Bank Statement *           │
│ [📁 Choose File]           │
│                            │
│ ━━━━━━━━━━━━━━━━━━━━━━    │
│                            │
│ [Submit Application]       │
│                            │
└────────────────────────────┘
```

---

## Color Scheme

### Primary Colors
- **Main Blue:** #0073aa (buttons, headers, links)
- **Dark Blue:** #005a87 (hover states)
- **Light Blue:** #e3f2fd (info boxes)

### Status Colors
- **Pending:** #f39c12 (Orange)
- **Approved:** #28a745 (Green)
- **Rejected:** #dc3545 (Red)
- **Under Review:** #2196f3 (Blue)

### Neutral Colors
- **Text:** #333333
- **Light Text:** #666666
- **Border:** #dddddd
- **Background:** #f9f9f9
- **White:** #ffffff

---

## Icons & Visual Elements

- ✓ Success checkmark (green)
- ✗ Error cross (red)
- 📁 File upload icon
- * Required field indicator (red)
- [PEN] Pending badge
- [APP] Approved badge
- [REJ] Rejected badge
- [REV] Under Review badge

---

**Note:** These are text representations of the UI. The actual plugin renders HTML with CSS styling for a professional appearance.
