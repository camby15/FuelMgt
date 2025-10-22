    <section class="features-overview">
        <div class="container">
            <h2 class="section-heading">Why Choose STAK?</h2>
            <p class="section-subheading">Empowering your business with the tools it needs to thrive.</p>
            <div class="features-list">
                <div class="feature-item">
                    <i class="fas icon fa-chart-line"></i>
                    <h3>Real-time Data Insights</h3>
                    <p>Gain instant access to accurate, actionable data for smarter decision-making.</p>
                </div>
                <div class="feature-item">
                    <i class="fas icon fa-plug"></i>
                    <h3>Seamless Integration with Tools</h3>
                    <p>Easily connect your favorite applications and maintain your current workflow.</p>
                </div>
                <div class="feature-item">
                    <i class="fas icon fa-layer-group"></i>
                    <h3>Scalable for Businesses of All Sizes</h3>
                    <p>Designed to grow with your company, from startups to enterprises.</p>
                </div>

                <div class="feature-item">
                    <i class="fas icon fa-shield-alt"></i>
                    <h3>Secure and Reliable</h3>
                    <p>Enterprise-grade security and uptime to keep your operations running smoothly.</p>
                </div>
            </div>
            <div class="cta-buttons key">
                <a href="{{ route('start') }}" class="btn-primary">Explore All Features</a>
                <a href="#demo" class="btn-secondary" data-modal-target="demoModal">Request a Demo</a>
            </div>
        </div>
    </section>

    <!-- Demo Request Modal with Floating Labels -->
    <div id="demoModal" class="modal">
        <div class="modal-content">
            <button class="modal-close">&times;</button>
            <div class="modal-header">
                <h3>Request a Demo</h3>
                <p>Fill out the form below and our team will contact you shortly</p>
            </div>
            <div class="modal-body">
                <form id="demoRequestForm">
                    <div class="form-group floating-input">
                        <input type="text" id="name" name="name" required>
                        <label for="name">Your Name</label>
                    </div>
                    
                    <div class="form-group floating-input">
                        <input type="email" id="email" name="email" required>
                        <label for="email">Email Address</label>
                    </div>
                    
                    <div class="form-group floating-input">
                        <input type="text" id="company" name="company">
                        <label for="company">Company Name</label>
                    </div>
                    
                    <div class="form-group floating-input">
                        <input type="tel" id="phone" name="phone" required>
                        <label for="phone">Phone Number</label>
                    </div>
                    
                    <button type="submit" class="submit">Submit Request</button>
                </form>
            </div>
            <div class="modal-footer">
                <p>Or contact us directly:</p>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:info@shrinqghana.com" class="contact-email">info@shrinqghana.com</a>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <a href="tel:+233240112912" class="contact-phone">+233 24 011 2912</a>
                        <span class="slash">/</span>
                        <a href="tel:+233541081200" class="contact-phone">+233 54 108 1200</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
       document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const demoModal = document.getElementById('demoModal');
    const demoButtons = document.querySelectorAll('[data-modal-target="demoModal"]');
    const closeButtons = document.querySelectorAll('.modal-close');
    const demoForm = document.getElementById('demoRequestForm');
    
    // Open modal function
    function openModal() {
        demoModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = getScrollbarWidth() + 'px';
        document.body.classList.add('modal-open');
        
        // Focus on first input
        const firstInput = demoForm.querySelector('input');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }
    
    // Close modal function
    function closeModal() {
        demoModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        document.body.style.paddingRight = '0';
        document.body.classList.remove('modal-open');
    }
    
    // Calculate scrollbar width
    function getScrollbarWidth() {
        return window.innerWidth - document.documentElement.clientWidth;
    }
    
    // Modal event listeners
    demoButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            openModal();
        });
    });
    
    closeButtons.forEach(button => {
        button.addEventListener('click', closeModal);
    });
    
    window.addEventListener('click', function(e) {
        if (e.target === demoModal) {
            closeModal();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && demoModal.style.display === 'block') {
            closeModal();
        }
    });
    
    // Form handling
    if (demoForm) {
        const inputs = demoForm.querySelectorAll('input');
        
        // Input validation
        function validateInput(input, showError = false) {
            const formGroup = input.closest('.floating-input');
            const errorMessage = formGroup.querySelector('.error-message') || createErrorMessage(formGroup);
            
            if (input.required && !input.value.trim()) {
                if (showError) {
                    formGroup.classList.add('error');
                    errorMessage.textContent = 'This field is required';
                    errorMessage.style.display = 'block';
                }
                return false;
            }
            
            // Email validation
            if (input.type === 'email' && input.value.trim()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(input.value)) {
                    if (showError) {
                        formGroup.classList.add('error');
                        errorMessage.textContent = 'Please enter a valid email';
                        errorMessage.style.display = 'block';
                    }
                    return false;
                }
            }
            
            // Phone validation
            if (input.type === 'tel' && input.value.trim()) {
                const phoneRegex = /^[\d\s\-+()]{8,}$/;
                if (!phoneRegex.test(input.value)) {
                    if (showError) {
                        formGroup.classList.add('error');
                        errorMessage.textContent = 'Please enter a valid phone number';
                        errorMessage.style.display = 'block';
                    }
                    return false;
                }
            }
            
            formGroup.classList.remove('error');
            errorMessage.style.display = 'none';
            return true;
        }
        
        function createErrorMessage(formGroup) {
            const errorMessage = document.createElement('div');
            errorMessage.className = 'error-message';
            formGroup.appendChild(errorMessage);
            return errorMessage;
        }
        
        // Input event listeners
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                validateInput(this);
            });
            
            input.addEventListener('blur', function() {
                validateInput(this, true);
            });
        });
        
        // Form submission
        demoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate all inputs
            let isValid = true;
            inputs.forEach(input => {
                if (!validateInput(input, true)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                // Scroll to first error
                const firstError = demoForm.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
                
                // Show validation error
                Swal.fire({
                    icon: 'error',
                    title: 'Form Incomplete',
                    text: 'Please fill all required fields correctly',
                    confirmButtonColor: '#069a9a'
                });
                return;
            }
            
            // Prepare form data
            const formData = {
                name: demoForm.querySelector('#name').value,
                email: demoForm.querySelector('#email').value,
                company: demoForm.querySelector('#company').value,
                phone: demoForm.querySelector('#phone').value
            };
            
            // Submit form
            submitFormData(formData);
        });
        
        // Form submission function
        function submitFormData(formData) {
            const submitBtn = demoForm.querySelector('.submit');
            const originalText = submitBtn.textContent;
            
            // Show loading state
            submitBtn.textContent = 'Sending...';
            submitBtn.disabled = true;
            
            // Show loading alert
            Swal.fire({
                title: 'Processing your request',
                html: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send request
            fetch("{{ route('demo.request') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                Swal.close();
                
                if (data.success) {
                    // Success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Request Received!',
                        text: data.message,
                        confirmButtonColor: '#069a9a',
                        timer: 3000
                    });
                    
                    // Reset form and close modal
                    demoForm.reset();
                    closeModal();
                } else {
                    throw new Error(data.message || 'Submission failed');
                }
            })
            .catch(error => {
                Swal.close();
                
                // Error message
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    text: error.message || 'An error occurred. Please try again.',
                    confirmButtonColor: '#069a9a'
                });
                
                // Log error to console
                console.error('Error:', error);
            })
            .finally(() => {
                // Reset button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        }
    }
});
        </script>