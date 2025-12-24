/**
 * Application Form Plugin - Admin JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Confirm before deleting application
        $('.button-link-delete').on('click', function(e) {
            if (!confirm('Are you sure you want to delete this application? This action cannot be undone.')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Auto-dismiss success messages
        setTimeout(function() {
            $('.notice.is-dismissible').fadeOut();
        }, 5000);
        
        // Application list table row highlighting
        $('.wp-list-table tbody tr').hover(
            function() {
                $(this).addClass('hover');
            },
            function() {
                $(this).removeClass('hover');
            }
        );
        
        // Quick status update (if needed in future)
        $('.afp-quick-status').on('change', function() {
            const $this = $(this);
            const appId = $this.data('app-id');
            const newStatus = $this.val();
            
            if (confirm('Change status to "' + newStatus + '"?')) {
                // AJAX call to update status
                // This can be implemented if needed
                console.log('Updating application #' + appId + ' to status: ' + newStatus);
            }
        });
        
        // Search form enhancement
        $('input[type="search"]').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            
            if (searchTerm.length === 0) {
                return;
            }
            
            // Could implement live search here if needed
        });
        
        // Copy shortcode to clipboard
        $('code').on('click', function() {
            const code = $(this).text();
            
            // Create temporary input
            const $temp = $('<input>');
            $('body').append($temp);
            $temp.val(code).select();
            document.execCommand('copy');
            $temp.remove();
            
            // Show feedback
            $(this).after('<span class="copied-message" style="color: #46b450; margin-left: 10px;">Copied!</span>');
            setTimeout(function() {
                $('.copied-message').fadeOut(function() {
                    $(this).remove();
                });
            }, 2000);
        });
        
        // Print application details
        $('.afp-print-application').on('click', function(e) {
            e.preventDefault();
            window.print();
        });
        
        // Export selected applications
        $('.afp-bulk-export').on('click', function(e) {
            const checkedBoxes = $('input[name="application_ids[]"]:checked');
            
            if (checkedBoxes.length === 0) {
                alert('Please select at least one application to export.');
                e.preventDefault();
                return false;
            }
        });
        
        // Filter applications by date range (if date filters exist)
        $('#afp-date-from, #afp-date-to').on('change', function() {
            const dateFrom = $('#afp-date-from').val();
            const dateTo = $('#afp-date-to').val();
            
            if (dateFrom && dateTo && dateFrom > dateTo) {
                alert('End date must be after start date.');
                $(this).val('');
            }
        });
        
        // Tooltips for status badges
        $('.afp-status-badge').attr('title', function() {
            const status = $(this).text().trim();
            const statusDescriptions = {
                'PENDING': 'Application is pending review',
                'APPROVED': 'Application has been approved',
                'REJECTED': 'Application has been rejected',
                'UNDER REVIEW': 'Application is currently being reviewed'
            };
            
            return statusDescriptions[status] || status;
        });
        
        // Expand/collapse notes section
        $('.afp-toggle-notes').on('click', function(e) {
            e.preventDefault();
            $(this).next('.afp-notes-content').slideToggle();
        });
        
        // Settings form validation
        $('form[action*="afp_save_settings"]').on('submit', function(e) {
            const email = $('input[name="admin_email"]').val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address.');
                e.preventDefault();
                return false;
            }
        });
        
        // Dashboard statistics animation (if on dashboard)
        $('.afp-stat-value').each(function() {
            const $this = $(this);
            const finalValue = parseInt($this.text());
            
            if (isNaN(finalValue)) {
                return;
            }
            
            $({ counter: 0 }).animate({ counter: finalValue }, {
                duration: 1000,
                easing: 'swing',
                step: function() {
                    $this.text(Math.ceil(this.counter));
                }
            });
        });
        
    });
    
})(jQuery);
