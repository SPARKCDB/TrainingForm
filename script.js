// ============================================
// Form Validation and Submission Handler
// ============================================

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        maxFileSize: 5 * 1024 * 1024, // 5MB in bytes
        allowedFileTypes: ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'],
        allowedExtensions: ['.pdf', '.jpg', '.jpeg', '.png']
    };

    // DOM Elements
    const form = document.getElementById('applicationForm');
    const submitBtn = document.getElementById('submitBtn');
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    const fileInputs = document.querySelectorAll('input[type="file"]');

    // Validation Rules
    const validationRules = {
        fullName: {
            required: true,
            minLength: 2,
            pattern: /^[a-zA-Z\s'-]+$/,
            message: 'Please enter a valid full name (letters, spaces, hyphens, and apostrophes only)'
        },
        nric: {
            required: true,
            pattern: /^[STFG]\d{7}[A-Z]$/i,
            message: 'Please enter a valid NRIC/FIN (e.g., S1234567A)'
        },
        contact: {
            required: true,
            pattern: /^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,9}$/,
            message: 'Please enter a valid contact number'
        },
        email: {
            required: true,
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            message: 'Please enter a valid email address'
        },
        address: {
            required: true,
            minLength: 10,
            message: 'Please enter your complete residential address (minimum 10 characters)'
        },
        course: {
            required: true,
            message: 'Please select a course'
        },
        terms: {
            required: true,
            message: 'You must agree to the terms and conditions'
        }
    };

    // Initialize
    function init() {
        setupFileInputs();
        setupFormValidation();
        setupSubmitHandler();
    }

    // Setup file input handlers
    function setupFileInputs() {
        fileInputs.forEach(input => {
            const label = input.closest('.upload-group');
            const filenameSpan = label.querySelector('.file-name');

            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const errorSpan = document.getElementById(`${input.id}-error`);

                if (file) {
                    // Validate file
                    const validation = validateFile(file);
                    
                    if (validation.valid) {
                        filenameSpan.textContent = `✓ ${file.name} (${formatFileSize(file.size)})`;
                        label.classList.add('has-file');
                        errorSpan.textContent = '';
                        input.classList.remove('error');
                    } else {
                        filenameSpan.textContent = 'No file chosen';
                        label.classList.remove('has-file');
                        errorSpan.textContent = validation.message;
                        input.classList.add('error');
                        input.value = ''; // Clear invalid file
                    }
                } else {
                    filenameSpan.textContent = 'No file chosen';
                    label.classList.remove('has-file');
                }
            });
        });
    }

    // Validate file
    function validateFile(file) {
        // Check file size
        if (file.size > CONFIG.maxFileSize) {
            return {
                valid: false,
                message: `File size must be less than ${formatFileSize(CONFIG.maxFileSize)}`
            };
        }

        // Check file type
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        if (!CONFIG.allowedExtensions.includes(fileExtension)) {
            return {
                valid: false,
                message: 'Only PDF, JPG, and PNG files are allowed'
            };
        }

        // Check MIME type
        if (!CONFIG.allowedFileTypes.includes(file.type)) {
            return {
                valid: false,
                message: 'Invalid file type. Please upload PDF, JPG, or PNG files only'
            };
        }

        return { valid: true };
    }

    // Format file size for display
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Setup real-time form validation
    function setupFormValidation() {
        // Add blur event listeners for real-time validation
        Object.keys(validationRules).forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field && field.type !== 'file') {
                field.addEventListener('blur', () => validateField(field));
                field.addEventListener('input', () => {
                    if (field.classList.contains('error')) {
                        validateField(field);
                    }
                });
            }
        });
    }

    // Validate individual field
    function validateField(field) {
        const fieldName = field.id;
        const rules = validationRules[fieldName];
        const errorSpan = document.getElementById(`${fieldName}-error`);
        let isValid = true;
        let errorMessage = '';

        if (!rules) return true;

        // Check if required
        if (rules.required) {
            if (field.type === 'checkbox') {
                if (!field.checked) {
                    isValid = false;
                    errorMessage = rules.message || 'This field is required';
                }
            } else if (!field.value.trim()) {
                isValid = false;
                errorMessage = rules.message || 'This field is required';
            }
        }

        // Check minimum length
        if (isValid && rules.minLength && field.value.trim().length < rules.minLength) {
            isValid = false;
            errorMessage = rules.message || `Minimum ${rules.minLength} characters required`;
        }

        // Check pattern
        if (isValid && rules.pattern && field.value.trim()) {
            if (!rules.pattern.test(field.value.trim())) {
                isValid = false;
                errorMessage = rules.message || 'Invalid format';
            }
        }

        // Update UI
        if (isValid) {
            field.classList.remove('error');
            errorSpan.textContent = '';
        } else {
            field.classList.add('error');
            errorSpan.textContent = errorMessage;
        }

        return isValid;
    }

    // Validate all fields
    function validateAllFields() {
        let isValid = true;
        const fields = form.querySelectorAll('input, select, textarea');

        fields.forEach(field => {
            if (field.type === 'file') {
                // Validate file fields
                const errorSpan = document.getElementById(`${field.id}-error`);
                if (!field.files || field.files.length === 0) {
                    isValid = false;
                    errorSpan.textContent = 'Please upload this document';
                } else {
                    errorSpan.textContent = '';
                }
            } else if (validationRules[field.id]) {
                if (!validateField(field)) {
                    isValid = false;
                }
            }
        });

        return isValid;
    }

    // Setup form submission
    function setupSubmitHandler() {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Hide previous messages
            successMessage.style.display = 'none';
            errorMessage.style.display = 'none';

            // Validate all fields
            if (!validateAllFields()) {
                // Scroll to first error
                const firstError = form.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return;
            }

            // Disable submit button and show loading state
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            submitBtn.textContent = 'Submitting...';

            try {
                // Collect form data
                const formData = new FormData(form);

                // Here you would normally send the data to your backend
                // For GitHub Pages (static hosting), you have several options:
                
                // OPTION 1: Use a form service like Formspree
                // const response = await fetch('YOUR_FORMSPREE_ENDPOINT', {
                //     method: 'POST',
                //     body: formData,
                //     headers: {
                //         'Accept': 'application/json'
                //     }
                // });

                // OPTION 2: Use Google Forms
                // (You'd need to map fields to Google Form entry IDs)

                // OPTION 3: Use a serverless function (e.g., Netlify Functions, Vercel)
                
                // For demonstration, we'll simulate a successful submission
                await simulateSubmission(formData);

                // Show success message
                successMessage.style.display = 'flex';
                successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });

                // Reset form
                form.reset();
                
                // Reset file upload displays
                fileInputs.forEach(input => {
                    const label = input.closest('.upload-group');
                    const filenameSpan = label.querySelector('.file-name');
                    filenameSpan.textContent = 'No file chosen';
                    label.classList.remove('has-file');
                });

                // Log submission data (for development)
                console.log('Form submitted successfully!');
                logFormData(formData);

            } catch (error) {
                console.error('Submission error:', error);
                errorMessage.style.display = 'flex';
                errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
                submitBtn.innerHTML = `
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Submit Application
                `;
            }
        });
    }

    // Simulate form submission (for demonstration)
    function simulateSubmission(formData) {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve({ success: true });
            }, 1500);
        });
    }

    // Log form data to console (for development)
    function logFormData(formData) {
        console.log('=== Form Data ===');
        for (let [key, value] of formData.entries()) {
            if (value instanceof File) {
                console.log(`${key}: ${value.name} (${formatFileSize(value.size)})`);
            } else {
                console.log(`${key}: ${value}`);
            }
        }
        console.log('================');
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();

// ============================================
// Integration Instructions (for developers)
// ============================================

/*
TO INTEGRATE WITH A BACKEND SERVICE:

1. FORMSPREE (Easiest - Free tier available)
   - Sign up at https://formspree.io
   - Create a new form and get your endpoint URL
   - Replace the simulateSubmission function with:
   
   const response = await fetch('YOUR_FORMSPREE_ENDPOINT', {
       method: 'POST',
       body: formData,
       headers: {
           'Accept': 'application/json'
       }
   });
   if (!response.ok) throw new Error('Submission failed');

2. GOOGLE FORMS
   - Create a Google Form with matching fields
   - Get field entry IDs from the form's HTML
   - Map your fields to Google Form entries
   - Submit via fetch to Google Forms endpoint

3. NETLIFY FORMS
   - Add data-netlify="true" to the form element
   - Remove the JavaScript submission handler
   - Netlify will automatically handle submissions

4. CUSTOM API
   - Create your own backend API endpoint
   - Update the fetch URL in setupSubmitHandler
   - Handle file uploads appropriately
   - Return appropriate success/error responses

5. EMAIL SERVICES
   - Use EmailJS (https://www.emailjs.com/)
   - Or SendGrid, Mailgun, etc. with serverless functions

For file uploads, consider using:
- Cloudinary (https://cloudinary.com)
- AWS S3 with signed URLs
- Firebase Storage
- Uploadcare (https://uploadcare.com)
*/
