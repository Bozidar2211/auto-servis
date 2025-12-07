/**
 * ADD CAR PAGE JAVASCRIPT
 * Real-time validacija, animacije i interaktivne funkcije
 */

document.addEventListener('DOMContentLoaded', () => {

    // ========== SCROLL TO TOP BUTTON ==========
    const scrollTopBtn = document.getElementById('scrollTop');
    
    if (scrollTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        });

        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ========== NAVBAR SCROLL EFFECT ==========
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 50) {
            navbar.style.boxShadow = '0 8px 30px rgba(0, 0, 0, 0.8)';
        } else {
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.5)';
        }
    });

    // ========== FORM ELEMENTS ==========
    const form = document.getElementById('addCarForm');
    const brandInput = document.getElementById('brand');
    const modelInput = document.getElementById('model');
    const yearInput = document.getElementById('year');
    const registrationInput = document.getElementById('registration');

    // ========== REAL-TIME VALIDATION ==========
    
    // Brand validation
    if (brandInput) {
        brandInput.addEventListener('input', function() {
            validateBrand(this);
        });

        brandInput.addEventListener('blur', function() {
            validateBrand(this);
        });
    }

    // Model validation
    if (modelInput) {
        modelInput.addEventListener('input', function() {
            validateModel(this);
        });

        modelInput.addEventListener('blur', function() {
            validateModel(this);
        });
    }

    // Year validation
    if (yearInput) {
        yearInput.addEventListener('input', function() {
            validateYear(this);
        });

        yearInput.addEventListener('blur', function() {
            validateYear(this);
        });
    }

    // Registration validation
    if (registrationInput) {
        registrationInput.addEventListener('input', function() {
            // Auto uppercase
            this.value = this.value.toUpperCase();
            validateRegistration(this);
        });

        registrationInput.addEventListener('blur', function() {
            validateRegistration(this);
        });
    }

    // ========== VALIDATION FUNCTIONS ==========
    
    function validateBrand(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('brand-error');
        
        if (value.length === 0) {
            setFieldState(input, 'invalid', 'Marka je obavezna', errorDiv);
            return false;
        } else if (value.length < 2) {
            setFieldState(input, 'invalid', 'Marka mora imati najmanje 2 karaktera', errorDiv);
            return false;
        } else if (value.length > 50) {
            setFieldState(input, 'invalid', 'Marka može imati maksimalno 50 karaktera', errorDiv);
            return false;
        } else if (!/^[a-zA-ZčćžšđČĆŽŠĐ\s-]+$/.test(value)) {
            setFieldState(input, 'invalid', 'Marka može sadržati samo slova, razmake i crticu', errorDiv);
            return false;
        } else {
            setFieldState(input, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateModel(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('model-error');
        
        if (value.length === 0) {
            setFieldState(input, 'invalid', 'Model je obavezan', errorDiv);
            return false;
        } else if (value.length > 50) {
            setFieldState(input, 'invalid', 'Model može imati maksimalno 50 karaktera', errorDiv);
            return false;
        } else {
            setFieldState(input, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateYear(input) {
        const value = input.value.trim();
        const year = parseInt(value);
        const currentYear = new Date().getFullYear();
        const errorDiv = document.getElementById('year-error');
        
        if (value.length === 0) {
            setFieldState(input, 'invalid', 'Godina je obavezna', errorDiv);
            return false;
        } else if (isNaN(year)) {
            setFieldState(input, 'invalid', 'Godina mora biti broj', errorDiv);
            return false;
        } else if (year < 1900) {
            setFieldState(input, 'invalid', 'Godina ne može biti pre 1900', errorDiv);
            return false;
        } else if (year > currentYear + 1) {
            setFieldState(input, 'invalid', `Godina ne može biti posle ${currentYear + 1}`, errorDiv);
            return false;
        } else {
            setFieldState(input, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateRegistration(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('registration-error');
        
        if (value.length === 0) {
            setFieldState(input, 'invalid', 'Registarska oznaka je obavezna', errorDiv);
            return false;
        } else if (value.length < 5) {
            setFieldState(input, 'invalid', 'Registarska oznaka mora imati najmanje 5 karaktera', errorDiv);
            return false;
        } else if (value.length > 10) {
            setFieldState(input, 'invalid', 'Registarska oznaka može imati maksimalno 10 karaktera', errorDiv);
            return false;
        } else if (!/^[A-Z0-9\-]+$/.test(value)) {
            setFieldState(input, 'invalid', 'Registarska oznaka može sadržati samo velika slova, brojeve i crticu', errorDiv);
            return false;
        } else {
            setFieldState(input, 'valid', '', errorDiv);
            return true;
        }
    }

    function setFieldState(input, state, message, errorDiv) {
        input.classList.remove('valid', 'invalid', 'error', 'success');
        
        if (state === 'valid') {
            input.classList.add('valid', 'success');
            if (errorDiv) {
                errorDiv.textContent = '';
                errorDiv.classList.remove('active');
            }
        } else if (state === 'invalid') {
            input.classList.add('invalid', 'error');
            if (errorDiv) {
                errorDiv.textContent = message;
                errorDiv.classList.add('active');
            }
        }
    }

    // ========== FORM SUBMISSION ==========
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate all fields
            const isBrandValid = validateBrand(brandInput);
            const isModelValid = validateModel(modelInput);
            const isYearValid = validateYear(yearInput);
            const isRegistrationValid = validateRegistration(registrationInput);
            
            if (isBrandValid && isModelValid && isYearValid && isRegistrationValid) {
                // Show loading state
                const submitBtn = form.querySelector('.btn-submit');
                submitBtn.classList.add('loading');
                
                // Animate progress to step 2
                animateProgressStep();
                
                // Submit form after animation
                setTimeout(() => {
                    form.submit();
                }, 1500);
            } else {
                showNotification('Molimo ispravite greške u formi', 'error');
                
                // Focus first invalid field
                const firstInvalid = form.querySelector('.invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }

    // ========== PROGRESS STEP ANIMATION ==========
    function animateProgressStep() {
        const step2 = document.querySelectorAll('.progress-step')[1];
        const line = document.querySelector('.progress-line');
        
        if (step2 && line) {
            line.style.background = 'linear-gradient(90deg, var(--primary) 0%, rgba(240, 173, 78, 0.2) 100%)';
            line.style.transition = 'background 1s ease';
            
            setTimeout(() => {
                step2.classList.add('active');
            }, 500);
        }
    }

    // ========== POPULAR BRANDS ==========
    const brandButtons = document.querySelectorAll('.brand-btn');
    
    brandButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const brandName = this.getAttribute('data-brand');
            
            // Set brand value
            if (brandInput) {
                brandInput.value = brandName;
                brandInput.focus();
                validateBrand(brandInput);
                
                // Visual feedback
                brandButtons.forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
                
                // Scroll to form
                brandInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                showNotification(`Izabrana marka: ${brandName}`, 'success');
            }
        });
    });

    // ========== INPUT FOCUS ANIMATIONS ==========
    const allInputs = document.querySelectorAll('.form-control');
    
    allInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.closest('.form-group').style.transform = 'scale(1.01)';
            this.closest('.form-group').style.transition = 'transform 0.3s ease';
        });
        
        input.addEventListener('blur', function() {
            this.closest('.form-group').style.transform = 'scale(1)';
        });
    });

    // ========== NOTIFICATION SYSTEM ==========
    window.showNotification = function(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        
        const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
        notification.innerHTML = `
            <i class="fas fa-${icon}"></i>
            <span>${message}</span>
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 30px;
            background: ${type === 'success' ? 'rgba(40, 167, 69, 0.95)' : 'rgba(220, 53, 69, 0.95)'};
            color: #fff;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideInRight 0.4s ease-out;
            backdrop-filter: blur(10px);
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.4s ease-out';
            setTimeout(() => notification.remove(), 400);
        }, 3000);
    };

    // Add notification animations
    if (!document.querySelector('#notificationStyles')) {
        const style = document.createElement('style');
        style.id = 'notificationStyles';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // ========== AUTO-FILL YEAR ==========
    if (yearInput && yearInput.value === '') {
        const currentYear = new Date().getFullYear();
        yearInput.setAttribute('placeholder', currentYear.toString());
    }

    // ========== KEYBOARD SHORTCUTS ==========
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + Enter = Submit form
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            if (form) {
                form.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        }
        
        // Escape = Clear form
        if (e.key === 'Escape') {
            if (confirm('Da li želite da obrišete sve podatke u formi?')) {
                form.reset();
                allInputs.forEach(input => {
                    input.classList.remove('valid', 'invalid', 'error', 'success');
                });
                showNotification('Forma je resetovana', 'success');
            }
        }
    });

    // ========== PREVENT DOUBLE SUBMISSION ==========
    let isSubmitting = false;
    
    if (form) {
        form.addEventListener('submit', function() {
            if (isSubmitting) {
                return false;
            }
            isSubmitting = true;
        });
    }

    // ========== AUTO-SAVE DRAFT (Optional) ==========
    function saveDraft() {
        if (brandInput || modelInput || yearInput || registrationInput) {
            const draft = {
                brand: brandInput?.value || '',
                model: modelInput?.value || '',
                year: yearInput?.value || '',
                registration: registrationInput?.value || ''
            };
            
            localStorage.setItem('add_car_draft', JSON.stringify(draft));
        }
    }

    // Auto-save every 5 seconds
    setInterval(saveDraft, 5000);

    // Restore draft on page load
    const savedDraft = localStorage.getItem('add_car_draft');
    if (savedDraft) {
        try {
            const draft = JSON.parse(savedDraft);
            
            if (draft.brand || draft.model || draft.year || draft.registration) {
                if (confirm('Pronađen je sačuvan draft. Želite li da ga učitate?')) {
                    if (brandInput && draft.brand) brandInput.value = draft.brand;
                    if (modelInput && draft.model) modelInput.value = draft.model;
                    if (yearInput && draft.year) yearInput.value = draft.year;
                    if (registrationInput && draft.registration) registrationInput.value = draft.registration;
                    
                    showNotification('Draft je uspešno učitan!', 'success');
                }
            }
        } catch (e) {
            console.error('Error loading draft:', e);
        }
    }

    // Clear draft on successful submission
    if (form) {
        form.addEventListener('submit', function() {
            localStorage.removeItem('add_car_draft');
        });
    }

    // ========== REGISTRATION FORMAT HELPER ==========
    if (registrationInput) {
        registrationInput.addEventListener('input', function() {
            let value = this.value.toUpperCase();
            
            // Auto-format: Add dash after city code (e.g., BG-123-AB)
            // This is optional and can be customized
            value = value.replace(/[^A-Z0-9\-]/g, '');
            
            this.value = value;
        });
    }

    // ========== CONSOLE EASTER EGG ==========
    console.log('%c🚗 Add Car Form', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
    console.log('%cKeyboard shortcuts:', 'color: #888; font-weight: bold;');
    console.log('Ctrl/Cmd + Enter: Submit form');
    console.log('Escape: Clear form');
    console.log('%cAuto-save: Enabled (every 5 seconds)', 'color: #28a745;');

    // ========== ANIMATE ELEMENTS ON SCROLL ==========
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });

    // ========== FORM FIELD COUNTER ==========
    function updateFieldCounter() {
        let filledFields = 0;
        const totalFields = 4;
        
        if (brandInput && brandInput.value.trim()) filledFields++;
        if (modelInput && modelInput.value.trim()) filledFields++;
        if (yearInput && yearInput.value.trim()) filledFields++;
        if (registrationInput && registrationInput.value.trim()) filledFields++;
        
        const percentage = (filledFields / totalFields) * 100;
        
        // Update progress indicator if needed
        console.log(`Form progress: ${filledFields}/${totalFields} (${percentage.toFixed(0)}%)`);
    }

    allInputs.forEach(input => {
        input.addEventListener('input', updateFieldCounter);
    });

});

// ========== PERFORMANCE OPTIMIZATION ==========
document.addEventListener('visibilitychange', () => {
    const animatedBg = document.querySelector('.animated-bg');
    
    if (document.hidden) {
        if (animatedBg) animatedBg.style.animationPlayState = 'paused';
    } else {
        if (animatedBg) animatedBg.style.animationPlayState = 'running';
    }
});