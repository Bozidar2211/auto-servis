document.addEventListener('DOMContentLoaded', () => {
let currentStep = 1;
const totalSteps = 3;
// ========== STEP NAVIGATION ==========
const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');
const submitBtn = document.getElementById('submitBtn');
const form = document.getElementById('requestForm');

if (nextBtn) {
    nextBtn.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            goTo(currentStep + 1);
        }
    });
}

if (prevBtn) {
    prevBtn.addEventListener('click', () => {
        goTo(currentStep - 1);
    });
}

function goTo(step) {
    // Hide current section
    const currentSection = document.querySelector(`.form-section[data-section="${currentStep}"]`);
    if (currentSection) {
        currentSection.classList.remove('active');
    }

    // Update current step
    currentStep = step;

    // Show new section
    const newSection = document.querySelector(`.form-section[data-section="${currentStep}"]`);
    if (newSection) {
        newSection.classList.add('active');
    }

    // Update progress indicators
    updateProgress();

    // Update buttons
    updateButtons();

    // Smooth scroll to form card (not to top of page)
    const formCard = document.querySelector('.form-card');
    if (formCard) {
        const navbarHeight = document.querySelector('.navbar').offsetHeight;
        const elementPosition = formCard.getBoundingClientRect().top + window.pageYOffset;
        const offsetPosition = elementPosition - navbarHeight - 20;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }
}

function updateProgress() {
    // Update step circles
    document.querySelectorAll('.step').forEach((step, index) => {
        const stepNum = index + 1;
        
        if (stepNum < currentStep) {
            step.classList.add('completed');
            step.classList.remove('active');
        } else if (stepNum === currentStep) {
            step.classList.add('active');
            step.classList.remove('completed');
        } else {
            step.classList.remove('active', 'completed');
        }
    });

    // Update step lines
    document.querySelectorAll('.step-line').forEach((line, index) => {
        if (index < currentStep - 1) {
            line.classList.add('completed');
        } else {
            line.classList.remove('completed');
        }
    });
}

function updateButtons() {
    // Show/hide prev button
    if (currentStep === 1) {
        prevBtn.style.display = 'none';
    } else {
        prevBtn.style.display = 'inline-flex';
    }

    // Show/hide next/submit buttons
    if (currentStep === totalSteps) {
        nextBtn.style.display = 'none';
        submitBtn.style.display = 'inline-flex';
    } else {
        nextBtn.style.display = 'inline-flex';
        submitBtn.style.display = 'none';
    }
}

// ========== VALIDATION ==========
function validateStep(step) {
    const section = document.querySelector(`.form-section[data-section="${step}"]`);
    
    if (step === 1) {
        // Validate car selection
        const carSelected = section.querySelector('input[name="car_id"]:checked');
        if (!carSelected) {
            showNotification('Molimo odaberite vozilo', 'warning');
            return false;
        }
        updatePreview();
        return true;
    }
    
    if (step === 2) {
        // Validate mechanic selection
        const mechanicSelected = section.querySelector('input[name="mechanic_id"]:checked');
        if (!mechanicSelected) {
            showNotification('Molimo odaberite mehaničara', 'warning');
            return false;
        }
        updatePreview();
        return true;
    }
    
    if (step === 3) {
        // Validate description
        const description = document.getElementById('description');
        if (description && description.value.trim().length < 10) {
            showNotification('Opis mora biti najmanje 10 karaktera', 'warning');
            description.focus();
            return false;
        }
        updatePreview();
        return true;
    }
    
    return true;
}

// ========== FORM PREVIEW ==========
function updatePreview() {
    // Update car preview
    const selectedCar = document.querySelector('input[name="car_id"]:checked');
    const previewCar = document.getElementById('previewCar');
    if (selectedCar && previewCar) {
        const carCard = selectedCar.closest('.car-option').querySelector('.car-info h3');
        if (carCard) {
            previewCar.textContent = carCard.textContent;
        }
    }

    // Update mechanic preview
    const selectedMechanic = document.querySelector('input[name="mechanic_id"]:checked');
    const previewMechanic = document.getElementById('previewMechanic');
    if (selectedMechanic && previewMechanic) {
        const mechanicCard = selectedMechanic.closest('.mechanic-option').querySelector('.mechanic-info h3');
        if (mechanicCard) {
            previewMechanic.textContent = mechanicCard.textContent;
        }
    }

    // Update urgency preview
    const selectedUrgency = document.querySelector('input[name="urgency"]:checked');
    const previewUrgency = document.getElementById('previewUrgency');
    if (selectedUrgency && previewUrgency) {
        const urgencyLabel = {
            'low': 'Nizak prioritet',
            'medium': 'Srednji prioritet',
            'high': 'Visoki prioritet'
        };
        previewUrgency.textContent = urgencyLabel[selectedUrgency.value] || 'Srednji prioritet';
    }

    // Update description preview
    const description = document.getElementById('description');
    const previewDescription = document.getElementById('previewDescription');
    if (description && previewDescription) {
        const descText = description.value.trim();
        if (descText) {
            previewDescription.textContent = 
                descText.length > 50 ? descText.substring(0, 50) + '...' : descText;
        } else {
            previewDescription.textContent = '-';
        }
    }
}

// Listen for changes
document.querySelectorAll('input[name="car_id"], input[name="mechanic_id"], input[name="urgency"]').forEach(input => {
    input.addEventListener('change', updatePreview);
});

const descriptionField = document.getElementById('description');
if (descriptionField) {
    descriptionField.addEventListener('input', updatePreview);
}

// ========== CHARACTER COUNTER ==========
if (descriptionField) {
    const charCounter = document.getElementById('charCounter');
    const maxChars = 500;

    descriptionField.addEventListener('input', function() {
        const length = this.value.length;
        if (charCounter) {
            charCounter.textContent = length;

            const charCountDiv = charCounter.parentElement;
            if (charCountDiv) {
                if (length > maxChars * 0.9) {
                    charCountDiv.classList.add('danger');
                    charCountDiv.classList.remove('warning');
                } else if (length > maxChars * 0.7) {
                    charCountDiv.classList.add('warning');
                    charCountDiv.classList.remove('danger');
                } else {
                    charCountDiv.classList.remove('warning', 'danger');
                }
            }

            if (length > maxChars) {
                this.value = this.value.substring(0, maxChars);
                charCounter.textContent = maxChars;
            }
        }
    });
}

// ========== FORM SUBMISSION ==========
if (form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Final validation
        if (!validateStep(1) || !validateStep(2) || !validateStep(3)) {
            showNotification('Molimo popunite sva obavezna polja', 'error');
            return;
        }

        // Show loading
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Slanje...';
        submitBtn.disabled = true;

        // Submit form
        setTimeout(() => {
            this.submit();
        }, 500);
    });
}

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

if (navbar) {
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 50) {
            navbar.style.boxShadow = '0 8px 30px rgba(0, 0, 0, 0.8)';
        } else {
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.5)';
        }
    });
}

// ========== NOTIFICATION SYSTEM ==========
window.showNotification = function(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    
    const colors = {
        success: '#28a745',
        error: '#dc3545',
        warning: '#ffc107',
        info: '#f0ad4e'
    };
    
    notification.innerHTML = `
        <i class="fas fa-${icons[type] || icons.info} me-2"></i>
        <span>${message}</span>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 30px;
        background: rgba(30, 30, 30, 0.98);
        border-left: 4px solid ${colors[type] || colors.info};
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
};

// ========== CARD ANIMATIONS ==========
const cards = document.querySelectorAll('.car-card, .mechanic-card');

cards.forEach((card, index) => {
    card.style.animation = `fadeInUp 0.6s ease-out ${index * 0.05}s backwards`;
});

// ========== RIPPLE EFFECT ==========
document.querySelectorAll('.btn-next, .btn-submit, .btn-back').forEach(button => {
    button.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.style.position = 'absolute';
        ripple.style.borderRadius = '50%';
        ripple.style.background = 'rgba(255, 255, 255, 0.5)';
        ripple.style.transform = 'scale(0)';
        ripple.style.animation = 'ripple 0.6s ease-out';
        ripple.style.pointerEvents = 'none';
        
        this.style.position = 'relative';
        this.style.overflow = 'hidden';
        this.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
    });
});

// ========== KEYBOARD SHORTCUTS ==========
document.addEventListener('keydown', (e) => {
    // Don't interfere with textarea input
    if (e.target.tagName === 'TEXTAREA') {
        return;
    }

    // Enter on step 1 or 2 = Next (but not on step 3 with textarea)
    if (e.key === 'Enter' && currentStep < totalSteps) {
        e.preventDefault();
        if (nextBtn.style.display !== 'none') {
            nextBtn.click();
        }
    }
    
    // Escape = Previous
    if (e.key === 'Escape' && currentStep > 1) {
        e.preventDefault();
        prevBtn.click();
    }
});

// ========== AUTO-ADVANCE ON SELECTION ==========
// Auto-advance to next step when car is selected
document.querySelectorAll('input[name="car_id"]').forEach(input => {
    input.addEventListener('change', () => {
        setTimeout(() => {
            if (currentStep === 1 && nextBtn.style.display !== 'none') {
                nextBtn.click();
            }
        }, 300);
    });
});

// Auto-advance to next step when mechanic is selected
document.querySelectorAll('input[name="mechanic_id"]').forEach(input => {
    input.addEventListener('change', () => {
        setTimeout(() => {
            if (currentStep === 2 && nextBtn.style.display !== 'none') {
                nextBtn.click();
            }
        }, 300);
    });
});

// ========== INITIALIZE ==========
updateProgress();
updateButtons();
updatePreview();

// Add animation to form wrapper
const formWrapper = document.querySelector('.form-wrapper');
if (formWrapper) {
    formWrapper.classList.add('fade-in');
}

// ========== DEBUG INFO ==========
console.log('%c🚗 Request Form', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
console.log('%cKeyboard shortcuts:\nEnter: Next step\nEscape: Previous step', 'color: #888;');
console.log('%cAuto-advance: Enabled (on car/mechanic selection)', 'color: #888;');
});
// ========== INJECT ANIMATION STYLES ==========
if (!document.getElementById('requestFormAnimations')) {
const style = document.createElement('style');
style.id = 'requestFormAnimations';
style.textContent = `
@keyframes ripple {
to {
transform: scale(4);
opacity: 0;
}
}
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