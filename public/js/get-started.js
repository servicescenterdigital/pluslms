// Get Started Page Functionality

document.addEventListener('DOMContentLoaded', function() {
    initSignupForm();
    initPasswordToggle();
    initAccordion();
    initProgressSteps();
});

// Signup form handling
function initSignupForm() {
    const signupForm = document.getElementById('signup-form');
    
    if (signupForm) {
        signupForm.addEventListener('submit', handleSignupSubmit);
        
        // Real-time validation
        const inputs = signupForm.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('blur', () => validateField(input));
            input.addEventListener('input', () => clearFieldError(input));
        });
    }
}

// Handle form submission
function handleSignupSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const formData = new FormData(form);
    
    // Validate all fields
    const inputs = form.querySelectorAll('input[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    // Check password confirmation
    const password = form.querySelector('#password').value;
    const confirmPassword = form.querySelector('#confirm-password').value;
    
    if (password !== confirmPassword) {
        showFieldError(form.querySelector('#confirm-password'), 'Passwords do not match');
        isValid = false;
    }
    
    // Check terms acceptance
    const termsCheckbox = form.querySelector('#terms');
    if (!termsCheckbox.checked) {
        showNotification('Please accept the Terms of Service to continue', 'error');
        isValid = false;
    }
    
    if (isValid) {
        // Show loading state
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
        }
        
        // Simulate account creation
        setTimeout(() => {
            // Success - redirect to dashboard or show success message
            showSuccessModal();
            
            // Reset form
            form.reset();
            
            // Reset button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-rocket"></i> Create Account';
            }
        }, 2000);
    }
}

// Password visibility toggle
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Validate individual field
function validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    const required = field.hasAttribute('required');
    
    clearFieldError(field);
    
    // Required field validation
    if (required && !value) {
        showFieldError(field, 'This field is required');
        return false;
    }
    
    // Email validation
    if (type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showFieldError(field, 'Please enter a valid email address');
            return false;
        }
    }
    
    // Password validation
    if (type === 'password' && value) {
        if (value.length < 8) {
            showFieldError(field, 'Password must be at least 8 characters long');
            return false;
        }
    }
    
    // Name validation
    if ((field.id === 'first-name' || field.id === 'last-name') && value) {
        if (value.length < 2) {
            showFieldError(field, 'Name must be at least 2 characters long');
            return false;
        }
    }
    
    return true;
}

// Show field error
function showFieldError(field, message) {
    field.classList.add('error');
    
    let errorElement = field.parentNode.querySelector('.field-error');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        field.parentNode.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
}

// Clear field error
function clearFieldError(field) {
    field.classList.remove('error');
    
    const errorElement = field.parentNode.querySelector('.field-error');
    if (errorElement) {
        errorElement.remove();
    }
}

// Success modal
function showSuccessModal() {
    const modal = document.createElement('div');
    modal.className = 'modal-overlay active';
    modal.innerHTML = `
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Account Created Successfully!</h3>
                <button class="modal-close" onclick="closeModal(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <p>Welcome to DGS-Pay! Your account has been created successfully.</p>
                <p>You can now access your dashboard and start building your payment integration.</p>
                <div class="success-actions">
                    <a href="/dashboard.html" class="btn-primary">
                        <i class="fas fa-tachometer-alt"></i>
                        Go to Dashboard
                    </a>
                    <a href="/docs" class="btn-secondary">
                        <i class="fas fa-book"></i>
                        View Documentation
                    </a>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Auto-redirect after 5 seconds
    setTimeout(() => {
        window.location.href = '/dashboard.html';
    }, 5000);
}

// Close modal
function closeModal(button) {
    const modal = button.closest('.modal-overlay');
    modal.remove();
}

// Accordion functionality for FAQ
function initAccordion() {
    const accordionItems = document.querySelectorAll('.accordion-item');
    
    accordionItems.forEach(item => {
        const header = item.querySelector('.accordion-header');
        
        header.addEventListener('click', () => {
            const isActive = item.classList.contains('active');
            
            // Close all other items
            accordionItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });
            
            // Toggle current item
            if (isActive) {
                item.classList.remove('active');
            } else {
                item.classList.add('active');
            }
        });
    });
}

// Progress steps animation
function initProgressSteps() {
    const stepItems = document.querySelectorAll('.step-item');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('step-revealed');
            }
        });
    }, { threshold: 0.5 });
    
    stepItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.2}s`;
        observer.observe(item);
    });
}

// Add get-started specific styles
const getStartedStyles = `
    <style>
        .hero-compact {
            min-height: 60vh;
            padding-top: calc(var(--navbar-height) + 2rem);
        }
        
        .signup-section {
            padding: 4rem 0;
            background: var(--bg-secondary);
        }
        
        .signup-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 4rem;
            align-items: start;
        }
        
        .signup-form-container {
            max-width: 500px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .password-input {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            transition: color 0.2s ease;
        }
        
        .password-toggle:hover {
            color: var(--text-primary);
        }
        
        .checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            cursor: pointer;
            font-size: 0.875rem;
            line-height: 1.5;
        }
        
        .checkbox-label input[type="checkbox"] {
            display: none;
        }
        
        .checkbox-custom {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        .checkbox-label input:checked + .checkbox-custom {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .checkbox-label input:checked + .checkbox-custom::after {
            content: '✓';
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        .checkbox-label a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .checkbox-label a:hover {
            text-decoration: underline;
        }
        
        .form-help {
            color: var(--text-tertiary);
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        
        .form-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }
        
        .form-footer a {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .signup-benefits {
            background: var(--bg-card);
            border-radius: var(--border-radius-xl);
            padding: 2rem;
            border: 1px solid var(--border-color);
        }
        
        .signup-benefits h3 {
            margin-bottom: 2rem;
            color: var(--text-primary);
            text-align: center;
        }
        
        .benefit-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .benefit-icon {
            width: 50px;
            height: 50px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .benefit-icon i {
            color: white;
            font-size: 1.25rem;
        }
        
        .benefit-content h4 {
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        
        .benefit-content p {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.5;
            margin: 0;
        }
        
        .benefit-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }
        
        .benefit-stat {
            text-align: center;
        }
        
        .stat-number {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .quick-start-section {
            padding: 6rem 0;
        }
        
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .step-item {
            text-align: center;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }
        
        .step-item.step-revealed {
            opacity: 1;
            transform: translateY(0);
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 1.5rem;
        }
        
        .step-content h3 {
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .step-content p {
            color: var(--text-secondary);
            line-height: 1.6;
        }
        
        .integration-examples {
            padding: 6rem 0;
            background: var(--bg-secondary);
        }
        
        .integration-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .integration-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-xl);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .integration-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }
        
        .integration-header {
            padding: 2rem 2rem 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .integration-header h3 {
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .integration-tags {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .tag {
            background: var(--bg-tertiary);
            color: var(--text-secondary);
            padding: 0.25rem 0.75rem;
            border-radius: var(--border-radius-md);
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .integration-preview {
            padding: 2rem;
            background: #1e293b;
            color: #e2e8f0;
            font-family: var(--font-mono);
            font-size: 0.875rem;
            line-height: 1.6;
            overflow-x: auto;
        }
        
        .integration-preview pre {
            margin: 0;
            white-space: pre-wrap;
        }
        
        .integration-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }
        
        .success-icon {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .success-icon i {
            font-size: 4rem;
            color: var(--success-color);
        }
        
        .success-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .modal-body {
            text-align: center;
        }
        
        .modal-body p {
            margin-bottom: 1rem;
            color: var(--text-secondary);
        }
        
        @media (max-width: 1024px) {
            .signup-grid {
                grid-template-columns: 1fr;
                gap: 3rem;
            }
            
            .signup-benefits {
                order: -1;
            }
            
            .benefit-stats {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .integration-grid {
                grid-template-columns: 1fr;
            }
            
            .steps-grid {
                grid-template-columns: 1fr;
            }
            
            .benefit-stats {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .success-actions {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
`;

// Inject get-started styles
document.head.insertAdjacentHTML('beforeend', getStartedStyles);