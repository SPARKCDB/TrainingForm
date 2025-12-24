<?php
/**
 * Form Handler Class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class AFP_Form_Handler {
    
    /**
     * Initialize form handler
     */
    public static function init() {
        add_action('wp_ajax_afp_submit_form', array(__CLASS__, 'process_form_submission'));
        add_action('wp_ajax_nopriv_afp_submit_form', array(__CLASS__, 'process_form_submission'));
    }
    
    /**
     * Render the application form
     */
    public static function render_form($atts = array()) {
        $title = isset($atts['title']) ? $atts['title'] : 'Course Application Form';
        $redirect = isset($atts['redirect']) ? $atts['redirect'] : '';
        
        ?>
        <div class="afp-form-wrapper">
            <div class="afp-form-container">
                <h2 class="afp-form-title"><?php echo esc_html($title); ?></h2>
                
                <div class="afp-messages"></div>
                
                <form id="afp-application-form" class="afp-form" method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field('afp_form_submit', 'afp_nonce'); ?>
                    
                    <div class="afp-form-section">
                        <h3 class="afp-section-title">Personal Information</h3>
                        
                        <div class="afp-form-row">
                            <div class="afp-form-group">
                                <label for="full_name" class="afp-label">
                                    Full Name <span class="required">*</span>
                                </label>
                                <input type="text" id="full_name" name="full_name" class="afp-input" required>
                            </div>
                        </div>
                        
                        <div class="afp-form-row afp-row-2">
                            <div class="afp-form-group">
                                <label for="nric_no" class="afp-label">
                                    NRIC No <span class="required">*</span>
                                </label>
                                <input type="text" id="nric_no" name="nric_no" class="afp-input" placeholder="e.g., 123456-12-1234" required>
                            </div>
                            
                            <div class="afp-form-group">
                                <label for="contact_no" class="afp-label">
                                    Contact No <span class="required">*</span>
                                </label>
                                <input type="tel" id="contact_no" name="contact_no" class="afp-input" placeholder="e.g., +60123456789" required>
                            </div>
                        </div>
                        
                        <div class="afp-form-row">
                            <div class="afp-form-group">
                                <label for="email" class="afp-label">
                                    Email Address <span class="required">*</span>
                                </label>
                                <input type="email" id="email" name="email" class="afp-input" required>
                            </div>
                        </div>
                        
                        <div class="afp-form-row">
                            <div class="afp-form-group">
                                <label for="home_address" class="afp-label">
                                    Home Address <span class="required">*</span>
                                </label>
                                <textarea id="home_address" name="home_address" class="afp-textarea" rows="3" required></textarea>
                            </div>
                        </div>
                        
                        <div class="afp-form-row">
                            <div class="afp-form-group">
                                <label for="course_interested" class="afp-label">
                                    Course Interested <span class="required">*</span>
                                </label>
                                <select id="course_interested" name="course_interested" class="afp-select" required>
                                    <option value="">Select a course</option>
                                    <option value="Diploma in Information Technology">Diploma in Information Technology</option>
                                    <option value="Diploma in Business Management">Diploma in Business Management</option>
                                    <option value="Diploma in Accounting">Diploma in Accounting</option>
                                    <option value="Diploma in Hospitality Management">Diploma in Hospitality Management</option>
                                    <option value="Diploma in Engineering">Diploma in Engineering</option>
                                    <option value="Certificate in Computer Science">Certificate in Computer Science</option>
                                    <option value="Certificate in Marketing">Certificate in Marketing</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="afp-form-section">
                        <h3 class="afp-section-title">Application Documents</h3>
                        <p class="afp-note">Please upload certified true copies (Salinan Diakui Sah) of the following documents:</p>
                        
                        <div class="afp-form-row">
                            <div class="afp-form-group">
                                <label for="nric_front" class="afp-label">
                                    NRIC Front Copy <span class="required">*</span>
                                </label>
                                <input type="file" id="nric_front" name="nric_front" class="afp-file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                                <small class="afp-help-text">Accepted formats: PDF, JPG, PNG (Max size: 5MB)</small>
                            </div>
                        </div>
                        
                        <div class="afp-form-row">
                            <div class="afp-form-group">
                                <label for="nric_back" class="afp-label">
                                    NRIC Back Copy <span class="required">*</span>
                                </label>
                                <input type="file" id="nric_back" name="nric_back" class="afp-file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                                <small class="afp-help-text">Accepted formats: PDF, JPG, PNG (Max size: 5MB)</small>
                            </div>
                        </div>
                        
                        <div class="afp-form-row">
                            <div class="afp-form-group">
                                <label for="birth_certificate" class="afp-label">
                                    Birth Certificate Copy <span class="required">*</span>
                                </label>
                                <input type="file" id="birth_certificate" name="birth_certificate" class="afp-file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                                <small class="afp-help-text">Accepted formats: PDF, JPG, PNG (Max size: 5MB)</small>
                            </div>
                        </div>
                        
                        <div class="afp-form-row">
                            <div class="afp-form-group">
                                <label for="bank_statement" class="afp-label">
                                    Bank Statement Front Page <span class="required">*</span>
                                </label>
                                <input type="file" id="bank_statement" name="bank_statement" class="afp-file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                                <small class="afp-help-text">Accepted formats: PDF, JPG, PNG (Max size: 5MB)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="afp-form-actions">
                        <button type="submit" class="afp-submit-btn">
                            <span class="afp-btn-text">Submit Application</span>
                            <span class="afp-spinner" style="display:none;">Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    /**
     * Process form submission via AJAX
     */
    public static function process_form_submission() {
        // Check nonce
        if (!isset($_POST['afp_nonce']) || !wp_verify_nonce($_POST['afp_nonce'], 'afp_form_submit')) {
            wp_send_json_error(array('message' => 'Security check failed. Please refresh the page and try again.'));
        }
        
        // Validate required fields
        $required_fields = array('full_name', 'nric_no', 'contact_no', 'email', 'home_address', 'course_interested');
        $errors = array();
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $errors[] = ucwords(str_replace('_', ' ', $field)) . ' is required.';
            }
        }
        
        // Validate email
        if (!empty($_POST['email']) && !is_email($_POST['email'])) {
            $errors[] = 'Please enter a valid email address.';
        }
        
        // Validate files
        $required_files = array('nric_front', 'nric_back', 'birth_certificate', 'bank_statement');
        
        foreach ($required_files as $file_field) {
            if (empty($_FILES[$file_field]['name'])) {
                $errors[] = ucwords(str_replace('_', ' ', $file_field)) . ' is required.';
            }
        }
        
        if (!empty($errors)) {
            wp_send_json_error(array('message' => implode('<br>', $errors)));
        }
        
        // Handle file uploads
        $uploaded_files = array();
        $upload_errors = array();
        
        foreach ($required_files as $file_field) {
            $result = self::handle_file_upload($file_field);
            
            if (is_wp_error($result)) {
                $upload_errors[] = $result->get_error_message();
            } else {
                $uploaded_files[$file_field . '_file'] = $result;
            }
        }
        
        if (!empty($upload_errors)) {
            // Delete any uploaded files if there were errors
            foreach ($uploaded_files as $file) {
                if (file_exists(AFP_UPLOAD_DIR . $file)) {
                    unlink(AFP_UPLOAD_DIR . $file);
                }
            }
            
            wp_send_json_error(array('message' => implode('<br>', $upload_errors)));
        }
        
        // Prepare data for database
        $data = array(
            'full_name' => sanitize_text_field($_POST['full_name']),
            'nric_no' => sanitize_text_field($_POST['nric_no']),
            'contact_no' => sanitize_text_field($_POST['contact_no']),
            'email' => sanitize_email($_POST['email']),
            'home_address' => sanitize_textarea_field($_POST['home_address']),
            'course_interested' => sanitize_text_field($_POST['course_interested']),
        );
        
        // Add uploaded file names
        $data = array_merge($data, $uploaded_files);
        
        // Insert into database
        $application_id = AFP_Database::insert_application($data);
        
        if ($application_id) {
            // Send email notification
            AFP_Email_Handler::send_admin_notification($application_id);
            AFP_Email_Handler::send_applicant_confirmation($data);
            
            wp_send_json_success(array(
                'message' => 'Your application has been submitted successfully! You will receive a confirmation email shortly.',
                'application_id' => $application_id
            ));
        } else {
            // Delete uploaded files on database error
            foreach ($uploaded_files as $file) {
                if (file_exists(AFP_UPLOAD_DIR . $file)) {
                    unlink(AFP_UPLOAD_DIR . $file);
                }
            }
            
            wp_send_json_error(array('message' => 'Failed to save your application. Please try again.'));
        }
    }
    
    /**
     * Handle file upload
     */
    private static function handle_file_upload($field_name) {
        if (empty($_FILES[$field_name]['name'])) {
            return new WP_Error('no_file', 'No file uploaded for ' . $field_name);
        }
        
        $file = $_FILES[$field_name];
        
        // Validate file size (5MB max)
        $max_size = 5 * 1024 * 1024; // 5MB in bytes
        if ($file['size'] > $max_size) {
            return new WP_Error('file_too_large', 'File size exceeds 5MB for ' . $field_name);
        }
        
        // Validate file type
        $allowed_types = array('application/pdf', 'image/jpeg', 'image/jpg', 'image/png');
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime_type, $allowed_types)) {
            return new WP_Error('invalid_file_type', 'Invalid file type for ' . $field_name . '. Only PDF, JPG, and PNG are allowed.');
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $field_name . '_' . time() . '_' . wp_generate_password(8, false) . '.' . $extension;
        
        // Move file to upload directory
        if (!file_exists(AFP_UPLOAD_DIR)) {
            wp_mkdir_p(AFP_UPLOAD_DIR);
        }
        
        if (move_uploaded_file($file['tmp_name'], AFP_UPLOAD_DIR . $filename)) {
            return $filename;
        }
        
        return new WP_Error('upload_failed', 'Failed to upload file for ' . $field_name);
    }
}

// Initialize the form handler
AFP_Form_Handler::init();
