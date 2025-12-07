/**
 * EDIT MODIFICATION PAGE JAVASCRIPT
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
    const form = document.getElementById('editModificationForm');
    const modDateInput = document.getElementById('mod_date');
    const descriptionTextarea = document.getElementById('description');
    const costInput = document.getElementById('cost');
    const originalCost = costInput?.value ? parseFloat(costInput.value) : 0;
    const priceDiffElement = document.getElementById('priceDiff');

    // ========== REAL-TIME VALIDATION ==========
    
    // Modification Date validation
    if (modDateInput) {
        modDateInput.addEventListener('change', function() {
            validateModDate(this);
        });

        modDateInput.addEventListener('blur', function() {
            validateModDate(this);
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

    // Cost validation and update price diff
    if (costInput) {
        costInput.addEventListener('input', function() {
            validateCost(this);
            updatePriceDiff();
        });

        costInput.addEventListener('blur', function() {
            validateCost(this);
            updatePriceDiff();
        });
    }

    // ========== VALIDATION FUNCTIONS ==========
    
    function validateModDate(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('mod_date-error');
        
        if (value.length === 0) {
            setFieldState(input, 'invalid', 'Datum je obavezan', errorDiv);
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

    // ========== PRICE DIFFERENCE UPDATE ==========
    function updatePriceDiff() {
        if (!priceDiffElement || !costInput) return;
        
        const newCost = parseFloat(costInput.value) || 0;
        const difference = newCost - originalCost;
        const formattedDiff = formatCurrency(Math.abs(difference));
        
        priceDiffElement.textContent = `${difference > 0 ? '+' : '-'} ${formattedDiff}`;
        
        // Update color based on difference
        priceDiffElement.classList.remove('positive', 'negative');
        if (difference > 0) {
            priceDiffElement.classList.add('positive');
        } else if (difference < 0) {
            priceDiffElement.classList.add('negative');
        }
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('sr-RS', {
            style: 'currency',
            currency: 'RSD',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount).replace('RSD', '').trim() + ' RSD';
    }

    // ========== FORM SUBMISSION ==========
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate all required fields
            const isModDateValid = validateModDate(modDateInput);
            const isDescriptionValid = validateDescription(descriptionTextarea);
            const isCostValid = validateCost(costInput);
            
            if (isModDateValid && isDescriptionValid && isCostValid) {
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
    if (modDateInput) {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const maxDate = `${year}-${month}-${day}`;
        
        modDateInput.setAttribute('max', maxDate);
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
        
        // Ctrl/Cmd + Z = Undo changes (reset form)
        if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
            e.preventDefault();
            if (confirm('Da li želite da poništite sve izmene?')) {
                form.reset();
                allInputs.forEach(input => {
                    input.classList.remove('valid', 'invalid', 'error', 'success');
                });
                updatePriceDiff();
                showNotification('Izmene su poništene', 'success');
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
        if (modDateInput || descriptionTextarea || costInput) {
            const draft = {
                modDate: modDateInput?.value || '',
                description: descriptionTextarea?.value || '',
                cost: costInput?.value || ''
            };
            
            localStorage.setItem('edit_modification_draft', JSON.stringify(draft));
        }
    }

    // Auto-save every 5 seconds
    setInterval(saveDraft, 5000);

    // Restore draft on page load
    const savedDraft = localStorage.getItem('edit_modification_draft');
    if (savedDraft) {
        try {
            const draft = JSON.parse(savedDraft);
            const currentValues = {
                modDate: modDateInput?.value || '',
                description: descriptionTextarea?.value || '',
                cost: costInput?.value || ''
            };
            
            // Only show restore prompt if there are differences
            if (JSON.stringify(draft) !== JSON.stringify(currentValues) && 
                Object.values(draft).some(v => v)) {
                if (confirm('Pronađene su izmene koje nisu sačuvane. Želite li da ih učitate?')) {
                    if (modDateInput && draft.modDate) modDateInput.value = draft.modDate;
                    if (descriptionTextarea && draft.description) descriptionTextarea.value = draft.description;
                    if (costInput && draft.cost) costInput.value = draft.cost;
                    
                    updatePriceDiff();
                    showNotification('Izmene su učitane!', 'success');
                }
            }
        } catch (e) {
            console.error('Error loading draft:', e);
        }
    }

    // Clear draft on successful submission
    if (form) {
        form.addEventListener('submit', function() {
            localStorage.removeItem('edit_modification_draft');
        });
    }

    // ========== DETECT CHANGES ==========
    const formChangeIndicator = document.createElement('style');
    formChangeIndicator.textContent = `
        .form-has-changes .form-header {
            border-bottom: 3px solid rgba(255, 193, 7, 0.5) !important;
        }
    `;
    document.head.appendChild(formChangeIndicator);

    let hasChanges = false;

    allInputs.forEach(input => {
        input.addEventListener('input', () => {
            hasChanges = true;
            document.querySelector('.edit-form-container').classList.add('form-has-changes');
        });
    });

    if (form) {
        form.addEventListener('submit', () => {
            hasChanges = false;
        });
    }

    window.addEventListener('beforeunload', (e) => {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    });

    // ========== CONSOLE EASTER EGG ==========
    console.log('%c✏️ Edit Modification Form', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
    console.log('%cKeyboard shortcuts:', 'color: #888; font-weight: bold;');
    console.log('Ctrl/Cmd + Enter: Submit form');
    console.log('Ctrl/Cmd + Z: Undo changes');
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

    // ========== INITIALIZE PRICE DIFF ==========
    updatePriceDiff();

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