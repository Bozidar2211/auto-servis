/**
 * ADD REMINDER PAGE JAVASCRIPT
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
    const form = document.getElementById('addReminderForm');
    const reminderTypeSelect = document.getElementById('reminder_type');
    const reminderDateInput = document.getElementById('reminder_date');
    const reminderTimeInput = document.getElementById('reminder_time');
    const prioritySelect = document.getElementById('priority');
    const noteTextarea = document.getElementById('note');
    const estimatedCostInput = document.getElementById('estimated_cost');
    const statusSelect = document.getElementById('status');

    // ========== REAL-TIME VALIDATION ==========
    
    // Reminder Type validation
    if (reminderTypeSelect) {
        reminderTypeSelect.addEventListener('change', function() {
            validateReminderType(this);
        });

        reminderTypeSelect.addEventListener('blur', function() {
            validateReminderType(this);
        });
    }

    // Reminder Date validation
    if (reminderDateInput) {
        reminderDateInput.addEventListener('change', function() {
            validateReminderDate(this);
        });

        reminderDateInput.addEventListener('blur', function() {
            validateReminderDate(this);
        });
    }

    // Reminder Time validation (optional)
    if (reminderTimeInput) {
        reminderTimeInput.addEventListener('change', function() {
            validateReminderTime(this);
        });
    }

    // Note validation
    if (noteTextarea) {
        noteTextarea.addEventListener('input', function() {
            validateNote(this);
        });

        noteTextarea.addEventListener('blur', function() {
            validateNote(this);
        });
    }

    // Estimated Cost validation
    if (estimatedCostInput) {
        estimatedCostInput.addEventListener('input', function() {
            validateCost(this);
        });

        estimatedCostInput.addEventListener('blur', function() {
            validateCost(this);
        });
    }

    // ========== VALIDATION FUNCTIONS ==========
    
    function validateReminderType(select) {
        const value = select.value.trim();
        const errorDiv = document.getElementById('reminder_type-error');
        
        if (value.length === 0) {
            setFieldState(select, 'invalid', 'Izbor tipa podsetnika je obavezan', errorDiv);
            return false;
        } else {
            setFieldState(select, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateReminderDate(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('reminder_date-error');
        
        if (value.length === 0) {
            setFieldState(input, 'invalid', 'Datum podsetnika je obavezan', errorDiv);
            return false;
        } else {
            const selectedDate = new Date(value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                setFieldState(input, 'invalid', 'Datum ne može biti u prošlosti', errorDiv);
                return false;
            }
            
            setFieldState(input, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateReminderTime(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('reminder_time-error');
        
        if (value === '') {
            // Time is optional
            setFieldState(input, 'valid', '', errorDiv);
            return true;
        }
        
        // Validate time format if filled
        if (!/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/.test(value)) {
            setFieldState(input, 'invalid', 'Vreme nije validno', errorDiv);
            return false;
        }
        
        setFieldState(input, 'valid', '', errorDiv);
        return true;
    }

    function validateNote(textarea) {
        const value = textarea.value.trim();
        const errorDiv = document.getElementById('note-error');
        
        if (value.length === 0) {
            setFieldState(textarea, 'invalid', 'Napomena je obavezna', errorDiv);
            return false;
        } else if (value.length < 5) {
            setFieldState(textarea, 'invalid', 'Napomena mora imati najmanje 5 karaktera', errorDiv);
            return false;
        } else if (value.length > 1000) {
            setFieldState(textarea, 'invalid', 'Napomena može imati maksimalno 1000 karaktera', errorDiv);
            return false;
        } else {
            setFieldState(textarea, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateCost(input) {
        const value = parseFloat(input.value);
        const errorDiv = document.getElementById('estimated_cost-error');
        
        if (input.value === '') {
            // Cost is optional
            setFieldState(input, 'valid', '', errorDiv);
            return true;
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

    // ========== FORM SUBMISSION ==========
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate all required fields
            const isReminderTypeValid = validateReminderType(reminderTypeSelect);
            const isReminderDateValid = validateReminderDate(reminderDateInput);
            const isNoteValid = validateNote(noteTextarea);
            
            if (isReminderTypeValid && isReminderDateValid && isNoteValid) {
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

    // ========== POPULAR REMINDERS ==========
    const reminderButtons = document.querySelectorAll('.reminder-btn');
    
    reminderButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const reminderType = this.getAttribute('data-type');
            
            // Set reminder type value
            if (reminderTypeSelect) {
                reminderTypeSelect.value = reminderType;
                reminderTypeSelect.dispatchEvent(new Event('change'));
                validateReminderType(reminderTypeSelect);
                
                // Visual feedback
                reminderButtons.forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
                
                // Scroll to form
                reminderTypeSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                showNotification(`Izabran podsetnik: ${reminderType}`, 'success');
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

    // ========== AUTO-SET MINIMUM DATE ==========
    if (reminderDateInput) {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const minDate = `${year}-${month}-${day}`;
        
        reminderDateInput.setAttribute('min', minDate);
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
                reminderButtons.forEach(b => b.classList.remove('selected'));
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
        if (reminderTypeSelect || reminderDateInput || noteTextarea) {
            const draft = {
                reminderType: reminderTypeSelect?.value || '',
                reminderDate: reminderDateInput?.value || '',
                reminderTime: reminderTimeInput?.value || '',
                priority: prioritySelect?.value || 'Normalna',
                note: noteTextarea?.value || '',
                estimatedCost: estimatedCostInput?.value || '',
                status: statusSelect?.value || 'Aktivna'
            };
            
            localStorage.setItem('add_reminder_draft', JSON.stringify(draft));
        }
    }

    // Auto-save every 5 seconds
    setInterval(saveDraft, 5000);

    // Restore draft on page load
    const savedDraft = localStorage.getItem('add_reminder_draft');
    if (savedDraft) {
        try {
            const draft = JSON.parse(savedDraft);
            
            if (Object.values(draft).some(v => v)) {
                if (confirm('Pronađen je sačuvan draft. Želite li da ga učitate?')) {
                    if (reminderTypeSelect && draft.reminderType) reminderTypeSelect.value = draft.reminderType;
                    if (reminderDateInput && draft.reminderDate) reminderDateInput.value = draft.reminderDate;
                    if (reminderTimeInput && draft.reminderTime) reminderTimeInput.value = draft.reminderTime;
                    if (prioritySelect && draft.priority) prioritySelect.value = draft.priority;
                    if (noteTextarea && draft.note) noteTextarea.value = draft.note;
                    if (estimatedCostInput && draft.estimatedCost) estimatedCostInput.value = draft.estimatedCost;
                    if (statusSelect && draft.status) statusSelect.value = draft.status;
                    
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
            localStorage.removeItem('add_reminder_draft');
        });
    }

    // ========== CONSOLE EASTER EGG ==========
    console.log('%c🔔 Add Reminder Form', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
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
        const requiredFields = 3; // reminder_type, reminder_date, note
        
        if (reminderTypeSelect && reminderTypeSelect.value.trim()) filledFields++;
        if (reminderDateInput && reminderDateInput.value.trim()) filledFields++;
        if (noteTextarea && noteTextarea.value.trim()) filledFields++;
        
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