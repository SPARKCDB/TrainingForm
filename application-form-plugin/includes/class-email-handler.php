<?php
/**
 * Email Handler Class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class AFP_Email_Handler {
    
    /**
     * Send admin notification
     */
    public static function send_admin_notification($application_id) {
        if (get_option('afp_enable_notifications') !== '1') {
            return false;
        }
        
        $application = AFP_Database::get_application($application_id);
        
        if (!$application) {
            return false;
        }
        
        $admin_email = get_option('afp_admin_email', get_option('admin_email'));
        $subject = 'New Course Application Received - ' . $application->full_name;
        
        $message = self::get_admin_email_template($application);
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
            'Reply-To: ' . $application->email
        );
        
        return wp_mail($admin_email, $subject, $message, $headers);
    }
    
    /**
     * Send applicant confirmation
     */
    public static function send_applicant_confirmation($data) {
        $to = $data['email'];
        $subject = 'Application Received - ' . get_bloginfo('name');
        
        $message = self::get_applicant_email_template($data);
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>'
        );
        
        return wp_mail($to, $subject, $message, $headers);
    }
    
    /**
     * Get admin email template
     */
    private static function get_admin_email_template($application) {
        $site_name = get_bloginfo('name');
        $view_url = admin_url('admin.php?page=application-forms&action=view&id=' . $application->id);
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #0073aa; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; }
                .info-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .info-table th { text-align: left; padding: 10px; background: #e9e9e9; width: 40%; }
                .info-table td { padding: 10px; background: white; }
                .button { display: inline-block; padding: 12px 24px; background: #0073aa; color: white; text-decoration: none; border-radius: 4px; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>New Application Received</h1>
                </div>
                
                <div class="content">
                    <p>A new course application has been submitted on <?php echo $site_name; ?>.</p>
                    
                    <h2>Applicant Information</h2>
                    <table class="info-table">
                        <tr>
                            <th>Full Name:</th>
                            <td><?php echo esc_html($application->full_name); ?></td>
                        </tr>
                        <tr>
                            <th>NRIC No:</th>
                            <td><?php echo esc_html($application->nric_no); ?></td>
                        </tr>
                        <tr>
                            <th>Contact No:</th>
                            <td><?php echo esc_html($application->contact_no); ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?php echo esc_html($application->email); ?></td>
                        </tr>
                        <tr>
                            <th>Home Address:</th>
                            <td><?php echo nl2br(esc_html($application->home_address)); ?></td>
                        </tr>
                        <tr>
                            <th>Course Interested:</th>
                            <td><?php echo esc_html($application->course_interested); ?></td>
                        </tr>
                        <tr>
                            <th>Submitted Date:</th>
                            <td><?php echo date('F j, Y \a\t g:i A', strtotime($application->submitted_date)); ?></td>
                        </tr>
                    </table>
                    
                    <p>Documents uploaded:</p>
                    <ul>
                        <li>NRIC Front Copy: <?php echo $application->nric_front_file ? '✓ Uploaded' : '✗ Not uploaded'; ?></li>
                        <li>NRIC Back Copy: <?php echo $application->nric_back_file ? '✓ Uploaded' : '✗ Not uploaded'; ?></li>
                        <li>Birth Certificate: <?php echo $application->birth_certificate_file ? '✓ Uploaded' : '✗ Not uploaded'; ?></li>
                        <li>Bank Statement: <?php echo $application->bank_statement_file ? '✓ Uploaded' : '✗ Not uploaded'; ?></li>
                    </ul>
                    
                    <p style="text-align: center;">
                        <a href="<?php echo $view_url; ?>" class="button">View Full Application</a>
                    </p>
                </div>
                
                <div class="footer">
                    <p>This is an automated notification from <?php echo $site_name; ?>.</p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get applicant email template
     */
    private static function get_applicant_email_template($data) {
        $site_name = get_bloginfo('name');
        $site_url = get_site_url();
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #0073aa; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; }
                .info-box { background: white; border-left: 4px solid #0073aa; padding: 15px; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Application Received</h1>
                </div>
                
                <div class="content">
                    <p>Dear <?php echo esc_html($data['full_name']); ?>,</p>
                    
                    <p>Thank you for submitting your course application to <?php echo $site_name; ?>. We have successfully received your application.</p>
                    
                    <div class="info-box">
                        <h3>Application Summary</h3>
                        <p><strong>Course:</strong> <?php echo esc_html($data['course_interested']); ?></p>
                        <p><strong>Submitted:</strong> <?php echo date('F j, Y \a\t g:i A'); ?></p>
                    </div>
                    
                    <p><strong>What happens next?</strong></p>
                    <ul>
                        <li>Our admissions team will review your application and documents</li>
                        <li>We will contact you within 3-5 business days</li>
                        <li>You will be notified via email once your application is processed</li>
                    </ul>
                    
                    <p>If you have any questions, please don't hesitate to contact us.</p>
                    
                    <p>Best regards,<br>
                    <strong><?php echo $site_name; ?></strong></p>
                </div>
                
                <div class="footer">
                    <p>This is an automated confirmation email from <?php echo $site_name; ?>.</p>
                    <p><a href="<?php echo $site_url; ?>"><?php echo $site_url; ?></a></p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Send status update notification to applicant
     */
    public static function send_status_update($application_id, $status) {
        $application = AFP_Database::get_application($application_id);
        
        if (!$application) {
            return false;
        }
        
        $to = $application->email;
        $subject = 'Application Status Update - ' . get_bloginfo('name');
        
        $site_name = get_bloginfo('name');
        $status_text = ucfirst($status);
        
        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #0073aa; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; }
                .status-box { background: white; border-left: 4px solid #0073aa; padding: 15px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Application Status Update</h1>
                </div>
                <div class='content'>
                    <p>Dear " . esc_html($application->full_name) . ",</p>
                    <p>Your course application for <strong>" . esc_html($application->course_interested) . "</strong> has been updated.</p>
                    <div class='status-box'>
                        <h3>New Status: " . $status_text . "</h3>
                        " . (!empty($application->notes) ? "<p>" . nl2br(esc_html($application->notes)) . "</p>" : "") . "
                    </div>
                    <p>If you have any questions, please contact us.</p>
                    <p>Best regards,<br><strong>$site_name</strong></p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>'
        );
        
        return wp_mail($to, $subject, $message, $headers);
    }
}
