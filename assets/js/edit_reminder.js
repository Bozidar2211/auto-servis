/**
 * EDIT REMINDER PAGE JAVASCRIPT
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
    const form = document.getElementById('editReminderForm');
    const reminderDateInput = document.getElementById('reminder_date');
    const noteTextarea = document.getElementById('note');
    const charCount = document.getElementById('charCount');
    const daysDiffElement = document.getElementById('daysDiff');
    const dateDisplayElement = document.getElementById('dateDisplay');

    // ========== REAL-TIME VALIDATION ==========
    
    // Reminder Date validation
    if (reminderDateInput) {
        reminderDateInput.addEventListener('change', function() {
            validateReminderDate(this);
            updateDaysDiff();
        });

        reminderDateInput.addEventListener('blur', function() {
            validateReminderDate(this);
        });
    }

    // Note validation
    if (noteTextarea) {
        noteTextarea.addEventListener('input', function() {
            validateNote(this);
            updateCharCount();
        });

        noteTextarea.addEventListener('blur', function() {
            validateNote(this);
        });
    }

    // ========== VALIDATION FUNCTIONS ==========
    
    function validateReminderDate(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('reminder_date-error');
        
        if (value.length === 0) {
            setFieldState(input, 'invalid', 'Datum je obavezan', errorDiv);
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

    function validateNote(textarea) {
        const value = textarea.value.trim();
        const errorDiv = document.getElementById('note-error');
        
        if (value.length === 0) {
            setFieldState(textarea, 'invalid', 'Napomena je obavezna', errorDiv);
            return false;
        } else if (value.length < 3) {
            setFieldState(textarea, 'invalid', 'Napomena mora imati najmanje 3 karaktera', errorDiv);
            return false;
        } else if (value.length > 200) {
            setFieldState(textarea, 'invalid', 'Napomena može imati maksimalno 200 karaktera', errorDiv);
            return false;
        } else {
            setFieldState(textarea, 'valid', '', errorDiv);
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

    // ========== CHARACTER COUNT UPDATE ==========
    function updateCharCount() {
        if (!charCount || !noteTextarea) return;
        
        const count = noteTextarea.value.length;
        charCount.textContent = count;

        const charCountElement = charCount.closest('.character-count');
        if (charCountElement) {
            charCountElement.classList.remove('warning', 'exceeded');
            
            if (count > 180) {
                charCountElement.classList.add('warning');
            }
            if (count > 200) {
                charCountElement.classList.add('exceeded');
            }
        }
    }

    // ========== DAYS DIFFERENCE UPDATE ==========
    function updateDaysDiff() {
        if (!daysDiffElement || !reminderDateInput) return;
        
        const selectedDate = new Date(reminderDateInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        selectedDate.setHours(0, 0, 0, 0);

        const differenceMs = selectedDate - today;
        const differenceDays = Math.ceil(differenceMs / (1000 * 60 * 60 * 24));

        // Format date display
        if (dateDisplayElement) {
            const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
            dateDisplayElement.textContent = selectedDate.toLocaleDateString('sr-RS', options);
        }

        // Update days diff
        daysDiffElement.classList.remove('soon', 'urgent');
        
        if (differenceDays === 0) {
            daysDiffElement.textContent = 'Danas!';
            daysDiffElement.classList.add('urgent');
        } else if (differenceDays === 1) {
            daysDiffElement.textContent = 'Sutra';
            daysDiffElement.classList.add('soon');
        } else if (differenceDays < 7) {
            daysDiffElement.textContent = `${differenceDays} dana`;
            daysDiffElement.classList.add('soon');
        } else if (differenceDays < 30) {
            daysDiffElement.textContent = `${differenceDays} dana`;
        } else if (differenceDays < 365) {
            const weeks = Math.floor(differenceDays / 7);
            daysDiffElement.textContent = `${weeks} nedelja`;
        } else {
            const years = Math.floor(differenceDays / 365);
            daysDiffElement.textContent = `${years} godinu`;
        }
    }

    // ========== FORM SUBMISSION ==========
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate all required fields
            const isDateValid = validateReminderDate(reminderDateInput);
            const isNoteValid = validateNote(noteTextarea);
            
            if (isDateValid && isNoteValid) {
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
                updateCharCount();
                updateDaysDiff();
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
        if (reminderDateInput || noteTextarea) {
            const draft = {
                reminderDate: reminderDateInput?.value || '',
                note: noteTextarea?.value || ''
            };
            
            localStorage.setItem('edit_reminder_draft', JSON.stringify(draft));
        }
    }

    // Auto-save every 5 seconds
    setInterval(saveDraft, 5000);

    // Restore draft on page load
    const savedDraft = localStorage.getItem('edit_reminder_draft');
    if (savedDraft) {
        try {
            const draft = JSON.parse(savedDraft);
            const currentValues = {
                reminderDate: reminderDateInput?.value || '',
                note: noteTextarea?.value || ''
            };
            
            // Only show restore prompt if there are differences
            if (JSON.stringify(draft) !== JSON.stringify(currentValues) && 
                Object.values(draft).some(v => v)) {
                if (confirm('Pronađene su izmene koje nisu sačuvane. Želite li da ih učitate?')) {
                    if (reminderDateInput && draft.reminderDate) reminderDateInput.value = draft.reminderDate;
                    if (noteTextarea && draft.note) noteTextarea.value = draft.note;
                    
                    updateCharCount();
                    updateDaysDiff();
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
            localStorage.removeItem('edit_reminder_draft');
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
    console.log('%c🔔 Edit Reminder Form', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
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

    // ========== INITIALIZE VALUES ==========
    updateCharCount();
    updateDaysDiff();

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