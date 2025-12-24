/**
 * Application Form Plugin - Frontend JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        const form = $('#afp-application-form');
        
        if (form.length === 0) {
            return;
        }
        
        // Form submission handler
        form.on('submit', function(e) {
            e.preventDefault();
            
            // Clear previous messages
            $('.afp-messages').html('');
            
            // Disable submit button
            const submitBtn = form.find('.afp-submit-btn');
            submitBtn.prop('disabled', true);
            submitBtn.find('.afp-btn-text').hide();
            submitBtn.find('.afp-spinner').show();
            
            // Add loading class to form
            form.addClass('loading');
            
            // Prepare form data
            const formData = new FormData(this);
            formData.append('action', 'afp_submit_form');
            
            // Submit via AJAX
            $.ajax({
                url: afpData.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        showMessage('success', response.data.message);
                        
                        // Reset form
                        form[0].reset();
                        
                        // Scroll to message
                        $('html, body').animate({
                            scrollTop: $('.afp-messages').offset().top - 100
                        }, 500);
                        
                    } else {
                        // Show error message
                        showMessage('error', response.data.message);
                    }
                },
                error: function(xhr, status, error) {
                    showMessage('error', 'An error occurred while submitting your application. Please try again.');
                    console.error('AJAX Error:', error);
                },
                complete: function() {
                    // Re-enable submit button
                    submitBtn.prop('disabled', false);
                    submitBtn.find('.afp-btn-text').show();
                    submitBtn.find('.afp-spinner').hide();
                    
                    // Remove loading class
                    form.removeClass('loading');
                }
            });
        });
        
        // Show message function
        function showMessage(type, message) {
            const messageClass = type === 'success' ? 'success' : 'error';
            const messageHtml = '<div class="afp-message ' + messageClass + '">' + message + '</div>';
            $('.afp-messages').html(messageHtml);
        }
        
        // File input validation
        const fileInputs = $('.afp-file-input');
        
        fileInputs.on('change', function() {
            const file = this.files[0];
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            
            if (file) {
                // Check file size
                if (file.size > maxSize) {
                    showMessage('error', 'File "' + file.name + '" is too large. Maximum size is 5MB.');
                    $(this).val('');
                    return false;
                }
                
                // Check file type
                if (!allowedTypes.includes(file.type)) {
                    showMessage('error', 'File "' + file.name + '" has an invalid type. Only PDF, JPG, and PNG files are allowed.');
                    $(this).val('');
                    return false;
                }
            }
        });
        
        // Real-time validation for email
        $('#email').on('blur', function() {
            const email = $(this).val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                $(this).addClass('error');
                showMessage('error', 'Please enter a valid email address.');
            } else {
                $(this).removeClass('error');
            }
        });
        
        // Real-time validation for NRIC
        $('#nric_no').on('blur', function() {
            const nric = $(this).val();
            const nricRegex = /^\d{6}-\d{2}-\d{4}$/;
            
            if (nric && !nricRegex.test(nric)) {
                $(this).addClass('error');
                showMessage('error', 'NRIC format should be: 123456-12-1234');
            } else {
                $(this).removeClass('error');
            }
        });
        
        // Real-time validation for contact number
        $('#contact_no').on('blur', function() {
            const contact = $(this).val();
            const contactRegex = /^[\d\s\+\-\(\)]+$/;
            
            if (contact && !contactRegex.test(contact)) {
                $(this).addClass('error');
                showMessage('error', 'Please enter a valid contact number.');
            } else {
                $(this).removeClass('error');
            }
        });
        
        // Clear error state on focus
        $('input, textarea, select').on('focus', function() {
            $(this).removeClass('error');
        });
        
        // Prevent form submission on Enter key (except in textarea)
        form.on('keypress', function(e) {
            if (e.which === 13 && !$(e.target).is('textarea')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Character counter for textarea (optional)
        const textareas = form.find('textarea');
        textareas.each(function() {
            const maxLength = 500;
            const $this = $(this);
            
            // Add counter element
            $this.after('<div class="char-counter" style="text-align: right; font-size: 12px; color: #666; margin-top: 5px;">0/' + maxLength + ' characters</div>');
            
            // Update counter on input
            $this.on('input', function() {
                const length = $(this).val().length;
                $this.next('.char-counter').text(length + '/' + maxLength + ' characters');
                
                if (length > maxLength) {
                    $this.next('.char-counter').css('color', '#dc3232');
                } else {
                    $this.next('.char-counter').css('color', '#666');
                }
            });
        });
    });
    
})(jQuery);
