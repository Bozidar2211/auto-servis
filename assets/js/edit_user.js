/**
 * EDIT USER PAGE JAVASCRIPT
 * Form validation, animations, and user experience enhancements
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

    // ========== FORM VALIDATION ==========
    const form = document.getElementById('editUserForm');
    
    if (form) {
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const roleSelect = document.getElementById('role');

        // Real-time validation
        usernameInput.addEventListener('input', function() {
            validateUsername(this);
        });

        emailInput.addEventListener('input', function() {
            validateEmail(this);
        });

        roleSelect.addEventListener('change', function() {
            validateRole(this);
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const isValid = validateForm();
            
            if (isValid) {
                showLoadingState();
                
                // Show confirmation dialog
                if (confirm('Da li ste sigurni da želite da sačuvate izmene?')) {
                    this.submit();
                } else {
                    hideLoadingState();
                }
            } else {
                showNotification('Molimo popunite sva polja ispravno!', 'error');
            }
        });

        // Reset button
        const resetBtn = form.querySelector('[type="reset"]');
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                if (!confirm('Da li ste sigurni da želite da resetujete formu?')) {
                    e.preventDefault();
                }
            });
        }
    }

    // ========== INPUT FOCUS ANIMATIONS ==========
    const inputs = document.querySelectorAll('.form-control');
    
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
            this.parentElement.style.transition = 'transform 0.3s ease';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

    // ========== ROLE BADGE ANIMATION ==========
    const roleBadge = document.querySelector('.user-role-badge');
    if (roleBadge) {
        roleBadge.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1) rotate(2deg)';
        });

        roleBadge.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    }

    // ========== CONSOLE EASTER EGG ==========
    console.log('%c✏️ Edit User Panel', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
    console.log('%cBuilt with ❤️ by Božidar AutoApp', 'color: #888;');

});

// ========== VALIDATION FUNCTIONS ==========
function validateUsername(input) {
    const value = input.value.trim();
    const minLength = 3;
    const maxLength = 50;
    
    if (value.length === 0) {
        setFieldState(input, 'error', 'Korisničko ime je obavezno');
        return false;
    } else if (value.length < minLength) {
        setFieldState(input, 'error', `Minimum ${minLength} karaktera`);
        return false;
    } else if (value.length > maxLength) {
        setFieldState(input, 'error', `Maximum ${maxLength} karaktera`);
        return false;
    } else if (!/^[a-zA-Z0-9_-]+$/.test(value)) {
        setFieldState(input, 'error', 'Samo slova, brojevi, _ i -');
        return false;
    } else {
        setFieldState(input, 'success', '');
        return true;
    }
}

function validateEmail(input) {
    const value = input.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (value.length === 0) {
        setFieldState(input, 'error', 'E-mail je obavezan');
        return false;
    } else if (!emailRegex.test(value)) {
        setFieldState(input, 'error', 'Nevalidna e-mail adresa');
        return false;
    } else {
        setFieldState(input, 'success', '');
        return true;
    }
}

function validateRole(select) {
    const value = select.value;
    
    if (!value || value === '') {
        setFieldState(select, 'error', 'Uloga je obavezna');
        return false;
    } else {
        setFieldState(select, 'success', '');
        return true;
    }
}

function validateForm() {
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const roleSelect = document.getElementById('role');
    
    const isUsernameValid = validateUsername(usernameInput);
    const isEmailValid = validateEmail(emailInput);
    const isRoleValid = validateRole(roleSelect);
    
    return isUsernameValid && isEmailValid && isRoleValid;
}

function setFieldState(input, state, message) {
    const formGroup = input.closest('.form-group');
    const helpText = formGroup.querySelector('.form-help');
    
    // Remove previous states
    input.classList.remove('error', 'success');
    
    if (state === 'error') {
        input.classList.add('error');
        if (helpText) {
            helpText.textContent = message;
            helpText.style.color = 'var(--danger)';
        }
    } else if (state === 'success') {
        input.classList.add('success');
        if (helpText) {
            // Restore original help text
            const originalHelp = {
                'username': 'Minimum 3 karaktera, maksimum 50',
                'email': 'Mora biti validna e-mail adresa',
                'role': 'Određuje nivo pristupa korisniku'
            };
            helpText.textContent = originalHelp[input.id] || '';
            helpText.style.color = 'var(--text-muted)';
        }
    }
}

// ========== LOADING STATE ==========
function showLoadingState() {
    const submitBtn = document.querySelector('.btn-submit');
    if (submitBtn) {
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Čuvanje...';
    }
}

function hideLoadingState() {
    const submitBtn = document.querySelector('.btn-submit');
    if (submitBtn) {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Sačuvaj izmene';
    }
}

// ========== NOTIFICATION SYSTEM ==========
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    
    const colors = {
        success: 'rgba(40, 167, 69, 0.95)',
        error: 'rgba(220, 53, 69, 0.95)',
        warning: 'rgba(255, 193, 7, 0.95)',
        info: 'rgba(240, 173, 78, 0.95)'
    };
    
    notification.innerHTML = `
        <i class="fas fa-${icons[type] || icons.info} me-2"></i>
        <span>${message}</span>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 30px;
        background: ${colors[type] || colors.info};
        color: #fff;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        z-index: 10000;
        font-weight: 600;
        display: flex;
        align-items: center;
        animation: slideInRight 0.4s ease-out;
        backdrop-filter: blur(10px);
        max-width: 400px;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.4s ease-out';
        setTimeout(() => notification.remove(), 400);
    }, 4000);
}

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
    // Ctrl/Cmd + S = Save form
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        const form = document.getElementById('editUserForm');
        if (form) {
            form.dispatchEvent(new Event('submit', { cancelable: true }));
        }
    }
    
    // Escape = Go back
    if (e.key === 'Escape') {
        const cancelBtn = document.querySelector('.btn-cancel');
        if (cancelBtn && confirm('Da li želite da otkažete izmene?')) {
            window.location.href = cancelBtn.href;
        }
    }
});

// ========== DETECT UNSAVED CHANGES ==========
let formChanged = false;

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('editUserForm');
    
    if (form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('change', () => {
                formChanged = true;
            });
        });

        // Warn before leaving with unsaved changes
        window.addEventListener('beforeunload', (e) => {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
        });

        // Reset flag on successful submit
        form.addEventListener('submit', () => {
            formChanged = false;
        });
    }
});

// ========== AUTO-SAVE DRAFT (OPTIONAL) ==========
function autoSaveDraft() {
    const form = document.getElementById('editUserForm');
    if (!form) return;
    
    const formData = new FormData(form);
    const draftData = {};
    
    for (let [key, value] of formData.entries()) {
        draftData[key] = value;
    }
    
    localStorage.setItem('edit_user_draft', JSON.stringify(draftData));
    console.log('Draft auto-saved');
}

// Auto-save every 30 seconds
setInterval(autoSaveDraft, 30000);

// ========== RESTORE DRAFT ==========
function restoreDraft() {
    const draft = localStorage.getItem('edit_user_draft');
    
    if (draft && confirm('Pronađen je sačuvan draft. Želite li da ga učitate?')) {
        const draftData = JSON.parse(draft);
        
        for (let [key, value] of Object.entries(draftData)) {
            const input = document.querySelector(`[name="${key}"]`);
            if (input && input.id !== 'id') { // Don't change ID field
                input.value = value;
            }
        }
        
        showNotification('Draft je uspešno učitan!', 'success');
    }
}

// ========== CLEAR DRAFT ON SUCCESSFUL SAVE ==========
window.addEventListener('load', () => {
    // Check if we came from successful save (you can add a URL parameter)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('saved') === 'true') {
        localStorage.removeItem('edit_user_draft');
        showNotification('Korisnik je uspešno ažuriran!', 'success');
    }
});

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

document.addEventListener('DOMContentLoaded', () => {
    const animatedElements = document.querySelectorAll('.form-container, .audit-trail');
    
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });
});

// ========== ROLE CHANGE WARNING ==========
document.addEventListener('DOMContentLoaded', () => {
    const roleSelect = document.getElementById('role');
    
    if (roleSelect) {
        const originalRole = roleSelect.value;
        
        roleSelect.addEventListener('change', function() {
            if (this.value !== originalRole) {
                showNotification(
                    'Pažnja: Promena uloge može uticati na pristup korisnika sistemu!',
                    'warning'
                );
            }
        });
    }
});

// ========== CHARACTER COUNTER ==========
document.addEventListener('DOMContentLoaded', () => {
    const usernameInput = document.getElementById('username');
    
    if (usernameInput) {
        const maxLength = usernameInput.getAttribute('maxlength') || 50;
        const helpText = usernameInput.closest('.form-group').querySelector('.form-help');
        
        usernameInput.addEventListener('input', function() {
            const remaining = maxLength - this.value.length;
            const originalText = helpText.dataset.originalText || helpText.textContent;
            
            if (!helpText.dataset.originalText) {
                helpText.dataset.originalText = originalText;
            }
            
            if (remaining < 10) {
                helpText.textContent = `${originalText} (${remaining} preostalih karaktera)`;
                helpText.style.color = remaining < 5 ? 'var(--danger)' : 'var(--warning)';
            } else {
                helpText.textContent = originalText;
                helpText.style.color = 'var(--text-muted)';
            }
        });
    }
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