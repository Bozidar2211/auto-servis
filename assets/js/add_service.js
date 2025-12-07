/**
 * ADD SERVICE PAGE JAVASCRIPT
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
    const form = document.getElementById('addServiceForm');
    const serviceDateInput = document.getElementById('service_date');
    const serviceTypeSelect = document.getElementById('service_type');
    const descriptionTextarea = document.getElementById('description');
    const costInput = document.getElementById('cost');
    const mileageInput = document.getElementById('mileage');
    const serviceProviderInput = document.getElementById('service_provider');
    const notesTextarea = document.getElementById('notes');

    // ========== REAL-TIME VALIDATION ==========
    
    // Service Date validation
    if (serviceDateInput) {
        serviceDateInput.addEventListener('change', function() {
            validateServiceDate(this);
        });

        serviceDateInput.addEventListener('blur', function() {
            validateServiceDate(this);
        });
    }

    // Service Type validation
    if (serviceTypeSelect) {
        serviceTypeSelect.addEventListener('change', function() {
            validateServiceType(this);
        });

        serviceTypeSelect.addEventListener('blur', function() {
            validateServiceType(this);
        });
    }

    // Description validation
    if (descriptionTextarea) {
        descriptionTextarea.addEventListener('input', function() {
            validateDescription(this);
        });

        descriptionTextarea.addEventListener('blur', function() {
            validateDescription(this);
        });
    }

    // Cost validation
    if (costInput) {
        costInput.addEventListener('input', function() {
            validateCost(this);
        });

        costInput.addEventListener('blur', function() {
            validateCost(this);
        });
    }

    // Mileage validation
    if (mileageInput) {
        mileageInput.addEventListener('input', function() {
            validateMileage(this);
        });

        mileageInput.addEventListener('blur', function() {
            validateMileage(this);
        });
    }

    // Service Provider validation
    if (serviceProviderInput) {
        serviceProviderInput.addEventListener('input', function() {
            validateServiceProvider(this);
        });

        serviceProviderInput.addEventListener('blur', function() {
            validateServiceProvider(this);
        });
    }

    // ========== VALIDATION FUNCTIONS ==========
    
    function validateServiceDate(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('service_date-error');
        
        if (value.length === 0) {
            setFieldState(input, 'invalid', 'Datum servisa je obavezan', errorDiv);
            return false;
        } else {
            const selectedDate = new Date(value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate > today) {
                setFieldState(input, 'invalid', 'Datum ne može biti u budućnosti', errorDiv);
                return false;
            }
            
            setFieldState(input, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateServiceType(select) {
        const value = select.value.trim();
        const errorDiv = document.getElementById('service_type-error');
        
        if (value.length === 0) {
            setFieldState(select, 'invalid', 'Izbor tipa servisa je obavezan', errorDiv);
            return false;
        } else {
            setFieldState(select, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateDescription(textarea) {
        const value = textarea.value.trim();
        const errorDiv = document.getElementById('description-error');
        
        if (value.length === 0) {
            setFieldState(textarea, 'invalid', 'Opis je obavezan', errorDiv);
            return false;
        } else if (value.length < 5) {
            setFieldState(textarea, 'invalid', 'Opis mora imati najmanje 5 karaktera', errorDiv);
            return false;
        } else if (value.length > 1000) {
            setFieldState(textarea, 'invalid', 'Opis može imati maksimalno 1000 karaktera', errorDiv);
            return false;
        } else {
            setFieldState(textarea, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateCost(input) {
        const value = parseFloat(input.value);
        const errorDiv = document.getElementById('cost-error');
        
        if (input.value === '') {
            setFieldState(input, 'invalid', 'Cena je obavezna', errorDiv);
            return false;
        }
        
        if (isNaN(value)) {
            setFieldState(input, 'invalid', 'Cena mora biti broj', errorDiv);
            return false;
        } else if (value < 0) {
            setFieldState(input, 'invalid', 'Cena ne može biti negativna', errorDiv);
            return false;
        } else if (value > 999999999) {
            setFieldState(input, 'invalid', 'Cena je prevelika', errorDiv);
            return false;
        } else {
            setFieldState(input, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateMileage(input) {
        const value = parseInt(input.value);
        const errorDiv = document.getElementById('mileage-error');
        
        if (input.value === '') {
            setFieldState(input, 'invalid', 'Kilometraža je obavezna', errorDiv);
            return false;
        }
        
        if (isNaN(value)) {
            setFieldState(input, 'invalid', 'Kilometraža mora biti broj', errorDiv);
            return false;
        } else if (value < 0) {
            setFieldState(input, 'invalid', 'Kilometraža ne može biti negativna', errorDiv);
            return false;
        } else if (value > 9999999) {
            setFieldState(input, 'invalid', 'Kilometraža je prevelika', errorDiv);
            return false;
        } else {
            setFieldState(input, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateServiceProvider(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('service_provider-error');
        
        if (value === '') {
            // Optional field
            setFieldState(input, 'valid', '', errorDiv);
            return true;
        }
        
        if (value.length < 2) {
            setFieldState(input, 'invalid', 'Naziv radnje mora imati najmanje 2 karaktera', errorDiv);
            return false;
        } else if (value.length > 100) {
            setFieldState(input, 'invalid', 'Naziv radnje može imati maksimalno 100 karaktera', errorDiv);
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
            
            // Validate all required fields
            const isServiceDateValid = validateServiceDate(serviceDateInput);
            const isServiceTypeValid = validateServiceType(serviceTypeSelect);
            const isDescriptionValid = validateDescription(descriptionTextarea);
            const isCostValid = validateCost(costInput);
            const isMileageValid = validateMileage(mileageInput);
            
            if (isServiceDateValid && isServiceTypeValid && isDescriptionValid && isCostValid && isMileageValid) {
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

    // ========== POPULAR SERVICES ==========
    const serviceButtons = document.querySelectorAll('.service-btn');
    
    serviceButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const serviceType = this.getAttribute('data-type');
            
            // Set service type value
            if (serviceTypeSelect) {
                serviceTypeSelect.value = serviceType;
                serviceTypeSelect.dispatchEvent(new Event('change'));
                validateServiceType(serviceTypeSelect);
                
                // Visual feedback
                serviceButtons.forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
                
                // Scroll to form
                serviceTypeSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                showNotification(`Izabran tip servisa: ${serviceType}`, 'success');
            }
        });
    });

    // ========== INPUT FOCUS ANIMATIONS ==========
    const allInputs = document.querySelectorAll('.form-control, .textarea-control');
    
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

    // ========== AUTO-SET MAXIMUM DATE ==========
    if (serviceDateInput) {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const maxDate = `${year}-${month}-${day}`;
        
        serviceDateInput.setAttribute('max', maxDate);
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
                serviceButtons.forEach(b => b.classList.remove('selected'));
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

    // ========== AUTO-SAVE DRAFT ==========
    function saveDraft() {
        if (serviceDateInput || serviceTypeSelect || descriptionTextarea) {
            const draft = {
                serviceDate: serviceDateInput?.value || '',
                serviceType: serviceTypeSelect?.value || '',
                description: descriptionTextarea?.value || '',
                cost: costInput?.value || '',
                mileage: mileageInput?.value || '',
                serviceProvider: serviceProviderInput?.value || '',
                notes: notesTextarea?.value || ''
            };
            
            localStorage.setItem('add_service_draft', JSON.stringify(draft));
        }
    }

    // Auto-save every 5 seconds
    setInterval(saveDraft, 5000);

    // Restore draft on page load
    const savedDraft = localStorage.getItem('add_service_draft');
    if (savedDraft) {
        try {
            const draft = JSON.parse(savedDraft);
            
            if (Object.values(draft).some(v => v)) {
                if (confirm('Pronađen je sačuvan draft. Želite li da ga učitate?')) {
                    if (serviceDateInput && draft.serviceDate) serviceDateInput.value = draft.serviceDate;
                    if (serviceTypeSelect && draft.serviceType) serviceTypeSelect.value = draft.serviceType;
                    if (descriptionTextarea && draft.description) descriptionTextarea.value = draft.description;
                    if (costInput && draft.cost) costInput.value = draft.cost;
                    if (mileageInput && draft.mileage) mileageInput.value = draft.mileage;
                    if (serviceProviderInput && draft.serviceProvider) serviceProviderInput.value = draft.serviceProvider;
                    if (notesTextarea && draft.notes) notesTextarea.value = draft.notes;
                    
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
            localStorage.removeItem('add_service_draft');
        });
    }

    // ========== CONSOLE EASTER EGG ==========
    console.log('%c🔧 Add Service Form', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
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
        const requiredFields = 5; // service_date, service_type, description, cost, mileage
        
        if (serviceDateInput && serviceDateInput.value.trim()) filledFields++;
        if (serviceTypeSelect && serviceTypeSelect.value.trim()) filledFields++;
        if (descriptionTextarea && descriptionTextarea.value.trim()) filledFields++;
        if (costInput && costInput.value.trim()) filledFields++;
        if (mileageInput && mileageInput.value.trim()) filledFields++;
        
        const percentage = (filledFields / requiredFields) * 100;
        
        console.log(`Form progress: ${filledFields}/${requiredFields} (${percentage.toFixed(0)}%)`);
    }

    allInputs.forEach(input => {
        input.addEventListener('input', updateFieldCounter);
        input.addEventListener('change', updateFieldCounter);
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