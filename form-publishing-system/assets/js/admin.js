/**
 * Form Publishing System - Admin JavaScript
 * 
 * @package FormPublishingSystem
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * Form Publishing Admin Object
     */
    const FormPublishingAdmin = {
        
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initModals();
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            // View history
            $(document).on('click', '.view-history', this.handleViewHistory.bind(this));
            
            // Modal close
            $(document).on('click', '.modal-close, .modal-cancel', this.closeModal);
            $(document).on('click', '.form-publishing-modal', function(e) {
                if ($(e.target).hasClass('form-publishing-modal')) {
                    FormPublishingAdmin.closeModal();
                }
            });
            
            // Publish form with AJAX
            $(document).on('click', '.ajax-publish-form', this.handlePublishForm.bind(this));
            
            // Unpublish form with AJAX
            $(document).on('click', '.ajax-unpublish-form', this.handleUnpublishForm.bind(this));
            
            // Schedule form
            $(document).on('click', '.schedule-form', this.handleScheduleForm.bind(this));
            
            // Add new form
            $(document).on('click', '#add-new-form', this.handleAddNewForm.bind(this));
            
            // Escape key to close modal
            $(document).on('keyup', function(e) {
                if (e.key === 'Escape') {
                    FormPublishingAdmin.closeModal();
                }
            });
        },
        
        /**
         * Initialize modals
         */
        initModals: function() {
            // Create modal container if it doesn't exist
            if ($('#form-publishing-modal').length === 0) {
                $('body').append(
                    '<div id="form-publishing-modal" class="form-publishing-modal">' +
                    '<div class="modal-content">' +
                    '<div class="modal-header">' +
                    '<h2 class="modal-title">Modal</h2>' +
                    '<button class="modal-close">&times;</button>' +
                    '</div>' +
                    '<div class="modal-body"></div>' +
                    '<div class="modal-footer">' +
                    '<button class="button modal-cancel">Cancel</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
            }
        },
        
        /**
         * Handle view history
         */
        handleViewHistory: function(e) {
            e.preventDefault();
            
            const formId = $(e.currentTarget).data('form-id');
            const $modal = $('#form-publishing-modal');
            const $modalBody = $modal.find('.modal-body');
            
            // Set modal title
            $modal.find('.modal-title').text('Publishing History');
            
            // Show loading
            $modalBody.html('<div style="text-align: center; padding: 40px;"><div class="form-publishing-loading"></div><p>Loading history...</p></div>');
            
            // Open modal
            $modal.fadeIn(200);
            
            // Fetch history
            $.ajax({
                url: formPublishingData.restUrl + '/forms/' + formId + '/history',
                method: 'GET',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', formPublishingData.restNonce);
                },
                success: function(response) {
                    if (response.history && response.history.length > 0) {
                        FormPublishingAdmin.renderHistory(response.history, $modalBody);
                    } else {
                        $modalBody.html('<p style="text-align: center; padding: 40px; color: #646970;">No history available for this form.</p>');
                    }
                },
                error: function(xhr) {
                    $modalBody.html('<div class="form-publishing-message error">Failed to load history. Please try again.</div>');
                }
            });
        },
        
        /**
         * Render history timeline
         */
        renderHistory: function(history, $container) {
            let html = '<ul class="history-timeline">';
            
            history.forEach(function(item) {
                const date = new Date(item.action_date);
                const formattedDate = date.toLocaleString();
                const actionClass = 'action-' + item.action;
                
                html += '<li class="history-item ' + actionClass + '">';
                html += '<div class="history-item-header">' + FormPublishingAdmin.formatAction(item.action) + '</div>';
                html += '<div class="history-item-meta">By User ID ' + item.user_id + ' on ' + formattedDate + '</div>';
                
                if (item.notes) {
                    html += '<div class="history-item-notes">' + FormPublishingAdmin.escapeHtml(item.notes) + '</div>';
                }
                
                html += '</li>';
            });
            
            html += '</ul>';
            
            $container.html(html);
        },
        
        /**
         * Format action name
         */
        formatAction: function(action) {
            const actions = {
                'published': 'Form Published',
                'unpublished': 'Form Unpublished',
                'scheduled': 'Form Scheduled',
                'draft': 'Saved as Draft'
            };
            
            return actions[action] || action.charAt(0).toUpperCase() + action.slice(1);
        },
        
        /**
         * Handle publish form
         */
        handlePublishForm: function(e) {
            e.preventDefault();
            
            const $button = $(e.currentTarget);
            const formId = $button.data('form-id');
            
            if (!confirm('Are you sure you want to publish this form? It will be visible to the public.')) {
                return;
            }
            
            $button.prop('disabled', true).text('Publishing...');
            
            $.ajax({
                url: formPublishingData.restUrl + '/forms/' + formId + '/publish',
                method: 'POST',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', formPublishingData.restNonce);
                },
                success: function(response) {
                    FormPublishingAdmin.showMessage('Form published successfully!', 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    const error = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to publish form';
                    FormPublishingAdmin.showMessage(error, 'error');
                    $button.prop('disabled', false).text('Publish');
                }
            });
        },
        
        /**
         * Handle unpublish form
         */
        handleUnpublishForm: function(e) {
            e.preventDefault();
            
            const $button = $(e.currentTarget);
            const formId = $button.data('form-id');
            const reason = prompt('Optional: Enter a reason for unpublishing this form:');
            
            if (reason === null) {
                return; // User cancelled
            }
            
            $button.prop('disabled', true).text('Unpublishing...');
            
            $.ajax({
                url: formPublishingData.restUrl + '/forms/' + formId + '/unpublish',
                method: 'POST',
                data: JSON.stringify({ reason: reason }),
                contentType: 'application/json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', formPublishingData.restNonce);
                },
                success: function(response) {
                    FormPublishingAdmin.showMessage('Form unpublished successfully!', 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    const error = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to unpublish form';
                    FormPublishingAdmin.showMessage(error, 'error');
                    $button.prop('disabled', false).text('Unpublish');
                }
            });
        },
        
        /**
         * Handle schedule form
         */
        handleScheduleForm: function(e) {
            e.preventDefault();
            
            const formId = $(e.currentTarget).data('form-id');
            const $modal = $('#form-publishing-modal');
            const $modalBody = $modal.find('.modal-body');
            
            // Set modal title
            $modal.find('.modal-title').text('Schedule Form Publishing');
            
            // Create form
            const html = 
                '<div class="form-builder">' +
                '<div class="form-builder-field">' +
                '<label for="schedule-date">Scheduled Publish Date and Time</label>' +
                '<input type="datetime-local" id="schedule-date" name="schedule-date" required>' +
                '<p class="description">Select when you want this form to be automatically published.</p>' +
                '</div>' +
                '</div>';
            
            $modalBody.html(html);
            
            // Update footer
            $modal.find('.modal-footer').html(
                '<button class="button modal-cancel">Cancel</button>' +
                '<button class="button button-primary" id="confirm-schedule">Schedule</button>'
            );
            
            // Open modal
            $modal.fadeIn(200);
            
            // Handle schedule confirmation
            $(document).off('click', '#confirm-schedule').on('click', '#confirm-schedule', function() {
                const scheduledDate = $('#schedule-date').val();
                
                if (!scheduledDate) {
                    alert('Please select a date and time.');
                    return;
                }
                
                // Convert to proper format
                const dateObj = new Date(scheduledDate);
                const mysqlDate = dateObj.toISOString().slice(0, 19).replace('T', ' ');
                
                $.ajax({
                    url: formPublishingData.restUrl + '/forms/' + formId + '/schedule',
                    method: 'POST',
                    data: JSON.stringify({ scheduled_date: mysqlDate }),
                    contentType: 'application/json',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', formPublishingData.restNonce);
                    },
                    success: function(response) {
                        FormPublishingAdmin.closeModal();
                        FormPublishingAdmin.showMessage('Form scheduled successfully!', 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to schedule form';
                        FormPublishingAdmin.showMessage(error, 'error');
                    }
                });
            });
        },
        
        /**
         * Handle add new form
         */
        handleAddNewForm: function(e) {
            e.preventDefault();
            
            const $modal = $('#form-publishing-modal');
            const $modalBody = $modal.find('.modal-body');
            
            // Set modal title
            $modal.find('.modal-title').text('Create New Form');
            
            // Create form
            const html = 
                '<div class="form-builder">' +
                '<div class="form-builder-field">' +
                '<label for="form-title">Form Title *</label>' +
                '<input type="text" id="form-title" name="form-title" required>' +
                '</div>' +
                '<div class="form-builder-field">' +
                '<label for="form-description">Description</label>' +
                '<textarea id="form-description" name="form-description"></textarea>' +
                '<p class="description">Optional description for this form.</p>' +
                '</div>' +
                '<div class="form-publishing-message info">' +
                'Note: This is a simplified form creator. After creating the form, you can add fields and configure it further.' +
                '</div>' +
                '</div>';
            
            $modalBody.html(html);
            
            // Update footer
            $modal.find('.modal-footer').html(
                '<button class="button modal-cancel">Cancel</button>' +
                '<button class="button button-primary" id="confirm-create">Create Form</button>'
            );
            
            // Open modal
            $modal.fadeIn(200);
            
            // Handle create confirmation
            $(document).off('click', '#confirm-create').on('click', '#confirm-create', function() {
                const title = $('#form-title').val();
                const description = $('#form-description').val();
                
                if (!title) {
                    alert('Please enter a form title.');
                    return;
                }
                
                FormPublishingAdmin.showMessage('Creating form...', 'info');
                
                // Note: This would require a create endpoint
                // For now, just show a message
                FormPublishingAdmin.closeModal();
                FormPublishingAdmin.showMessage('Form creation requires database implementation. Sample form functionality demonstrated.', 'info');
            });
        },
        
        /**
         * Close modal
         */
        closeModal: function() {
            $('#form-publishing-modal').fadeOut(200);
        },
        
        /**
         * Show message
         */
        showMessage: function(message, type) {
            type = type || 'info';
            
            const $message = $('<div class="form-publishing-message ' + type + '">' + FormPublishingAdmin.escapeHtml(message) + '</div>');
            
            $('.form-publishing-admin').prepend($message);
            
            setTimeout(function() {
                $message.fadeOut(400, function() {
                    $(this).remove();
                });
            }, 5000);
        },
        
        /**
         * Escape HTML
         */
        escapeHtml: function(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    };
    
    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        FormPublishingAdmin.init();
    });
    
})(jQuery);
