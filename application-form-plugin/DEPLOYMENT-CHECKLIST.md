# Deployment Checklist

Use this checklist to ensure proper deployment of the Application Form Plugin.

## Pre-Deployment

### Environment Check
- [ ] WordPress 5.0+ installed
- [ ] PHP 7.2+ available
- [ ] MySQL 5.6+ running
- [ ] PHP `upload_max_filesize` ≥ 5MB
- [ ] PHP `post_max_size` ≥ 25MB
- [ ] PHP `memory_limit` ≥ 128MB
- [ ] SSL certificate installed (recommended)
- [ ] Backup system in place

### File Preparation
- [ ] All plugin files present
- [ ] No syntax errors in PHP files
- [ ] CSS and JS files not minified (or minified versions available)
- [ ] index.php files in all directories
- [ ] Correct file permissions (644 for files, 755 for directories)

### Customization Complete
- [ ] Course list updated to match your offerings
- [ ] Email templates reviewed and customized
- [ ] Colors and branding updated in CSS
- [ ] Admin email address configured
- [ ] Plugin name/details updated (if white-labeled)

## Deployment Steps

### 1. Upload Plugin
- [ ] Upload to `/wp-content/plugins/` directory
- [ ] Verify all files uploaded successfully
- [ ] Check file integrity (no corrupted files)
- [ ] Set correct ownership (usually www-data or hosting user)

### 2. Activate Plugin
- [ ] Login to WordPress admin
- [ ] Go to Plugins page
- [ ] Click "Activate" on Application Form Plugin
- [ ] Check for activation errors
- [ ] Verify "Application Forms" menu appears

### 3. Database Verification
- [ ] Open phpMyAdmin or database manager
- [ ] Confirm `wp_afp_applications` table exists
- [ ] Verify table structure is correct
- [ ] Check table permissions
- [ ] Test database connection

### 4. File System Check
- [ ] Verify `/wp-content/uploads/` is writable
- [ ] Confirm `application-documents` folder created
- [ ] Check `.htaccess` file in upload directory
- [ ] Verify folder permissions (755 or 775)
- [ ] Test write permissions

### 5. Configure Settings
- [ ] Navigate to Application Forms → Settings
- [ ] Enter admin notification email
- [ ] Enable email notifications
- [ ] Save settings
- [ ] Verify settings saved correctly

### 6. Create Form Page
- [ ] Create new page or edit existing
- [ ] Add shortcode: `[application_form]`
- [ ] Set appropriate page title
- [ ] Publish page
- [ ] Note page URL for testing

## Testing Phase

### Functional Testing

#### Form Display
- [ ] Form appears on page
- [ ] All fields visible
- [ ] Labels correct
- [ ] Required field indicators showing
- [ ] Course dropdown populated
- [ ] File upload buttons working
- [ ] Submit button visible

#### Form Validation
- [ ] Required field validation works
- [ ] Email format validation works
- [ ] NRIC format validation works
- [ ] Phone number validation works
- [ ] File type validation works (accepts PDF, JPG, PNG)
- [ ] File type rejection works (rejects other types)
- [ ] File size validation works (rejects files > 5MB)
- [ ] Error messages display correctly

#### Form Submission
- [ ] Test data fills all fields
- [ ] All 4 documents upload successfully
- [ ] Submit button shows loading state
- [ ] Success message displays
- [ ] Form clears after submission
- [ ] Page doesn't reload (AJAX working)

#### Email Notifications
- [ ] Admin receives notification email
- [ ] Applicant receives confirmation email
- [ ] Emails not going to spam
- [ ] Email content correct
- [ ] Email formatting correct
- [ ] Links in emails work

#### Admin Interface
- [ ] Application appears in admin list
- [ ] All data saved correctly
- [ ] Documents accessible
- [ ] Can view application details
- [ ] Can update status
- [ ] Can add notes
- [ ] Status update saves
- [ ] Can delete application
- [ ] CSV export works
- [ ] Search functionality works
- [ ] Filter by status works

### Browser Testing
- [ ] Chrome/Chromium (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile Chrome
- [ ] Mobile Safari

### Device Testing
- [ ] Desktop (1920x1080)
- [ ] Laptop (1366x768)
- [ ] Tablet portrait (768x1024)
- [ ] Tablet landscape (1024x768)
- [ ] Mobile (375x667)
- [ ] Mobile (414x896)

### Performance Testing
- [ ] Form loads quickly (< 2 seconds)
- [ ] File uploads complete successfully
- [ ] Large files (near 5MB) upload
- [ ] Multiple simultaneous uploads work
- [ ] Database queries optimized
- [ ] No JavaScript errors in console
- [ ] No PHP errors in logs

### Security Testing
- [ ] Nonce verification working
- [ ] SQL injection attempts blocked
- [ ] XSS attempts blocked
- [ ] Direct file access blocked
- [ ] Unauthorized access blocked
- [ ] File upload bypass attempts fail
- [ ] Admin capabilities enforced

## Post-Deployment

### Monitoring Setup
- [ ] Enable WordPress error logging
- [ ] Set up email monitoring
- [ ] Configure backup schedule
- [ ] Set up uptime monitoring
- [ ] Configure analytics (if needed)

### Documentation
- [ ] Update internal documentation
- [ ] Train staff on admin interface
- [ ] Create workflow documentation
- [ ] Document custom modifications
- [ ] Share admin login details securely

### User Communication
- [ ] Announce form availability
- [ ] Share application page URL
- [ ] Provide instructions to applicants
- [ ] Set up FAQ page
- [ ] Configure support contact

### Backup Verification
- [ ] Database backup working
- [ ] File backup includes upload directory
- [ ] Test restore procedure
- [ ] Schedule regular backups
- [ ] Document backup locations

## Production Checklist

### Week 1
- [ ] Monitor email delivery daily
- [ ] Check application submissions daily
- [ ] Review error logs daily
- [ ] Test form functionality
- [ ] Collect user feedback

### Week 2-4
- [ ] Review analytics
- [ ] Optimize based on feedback
- [ ] Address any issues
- [ ] Update documentation
- [ ] Plan improvements

### Monthly Tasks
- [ ] Export applications to CSV
- [ ] Review and archive old applications
- [ ] Check disk space
- [ ] Update WordPress and plugins
- [ ] Review security logs
- [ ] Test backup restore

## Rollback Plan

### If Critical Issues Arise
1. [ ] Deactivate plugin immediately
2. [ ] Export all applications to CSV
3. [ ] Backup upload directory
4. [ ] Note error messages
5. [ ] Review error logs
6. [ ] Contact support if needed
7. [ ] Test fix in staging
8. [ ] Re-deploy when fixed

### Emergency Contacts
- [ ] WordPress Admin: _______________
- [ ] Hosting Support: _______________
- [ ] Developer: _______________
- [ ] Database Admin: _______________

## Optimization Checklist

### Performance
- [ ] Enable caching
- [ ] Optimize images
- [ ] Minify CSS/JS (if needed)
- [ ] Enable GZIP compression
- [ ] Use CDN (if applicable)

### SEO
- [ ] Set page meta title
- [ ] Add meta description
- [ ] Configure breadcrumbs
- [ ] Submit to sitemap

### Accessibility
- [ ] Test with screen reader
- [ ] Check keyboard navigation
- [ ] Verify ARIA labels
- [ ] Test color contrast
- [ ] Add alt text to images

## Compliance Checklist

### Data Protection
- [ ] Privacy policy updated
- [ ] Terms of service updated
- [ ] Cookie notice (if applicable)
- [ ] Data retention policy documented
- [ ] GDPR compliance (if applicable)
- [ ] Data encryption enabled

### Legal
- [ ] Terms and conditions reviewed
- [ ] Disclaimer added
- [ ] Copyright notices correct
- [ ] License compliance verified

## Sign-Off

### Deployment Team
- [ ] Developer: _________________ Date: _______
- [ ] QA Tester: ________________ Date: _______
- [ ] Project Manager: ___________ Date: _______
- [ ] Client/Stakeholder: ________ Date: _______

### Production Approval
- [ ] All tests passed
- [ ] No critical issues
- [ ] Backup verified
- [ ] Rollback plan ready
- [ ] Team trained
- [ ] Documentation complete

**Deployment Status:** ☐ Approved ☐ Pending ☐ Rejected

**Go-Live Date:** _______________

**Notes:**
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________

---

**Remember:** Always test in a staging environment before deploying to production!
