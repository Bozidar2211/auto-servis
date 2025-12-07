/**
 * ADD MODIFICATION PAGE JAVASCRIPT
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
    const form = document.getElementById('addModificationForm');
    const carIdSelect = document.getElementById('car_id');
    const modTypeInput = document.getElementById('mod_type');
    const categorySelect = document.getElementById('category');
    const descriptionTextarea = document.getElementById('description');
    const installationDateInput = document.getElementById('installation_date');
    const installationCostInput = document.getElementById('installation_cost');
    const partsCostInput = document.getElementById('parts_cost');
    const totalCostDisplay = document.getElementById('total_cost');
    const statusSelect = document.getElementById('status');
    const warrantyInput = document.getElementById('warranty');
    const notesTextarea = document.getElementById('notes');

    // ========== REAL-TIME VALIDATION ==========
    
    // Car ID validation
    if (carIdSelect) {
        carIdSelect.addEventListener('change', function() {
            validateCarId(this);
        });

        carIdSelect.addEventListener('blur', function() {
            validateCarId(this);
        });
    }

    // Modification Type validation
    if (modTypeInput) {
        modTypeInput.addEventListener('input', function() {
            validateModType(this);
        });

        modTypeInput.addEventListener('blur', function() {
            validateModType(this);
        });
    }

    // Category validation
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            validateCategory(this);
        });

        categorySelect.addEventListener('blur', function() {
            validateCategory(this);
        });
    }

    // Installation Date validation
    if (installationDateInput) {
        installationDateInput.addEventListener('change', function() {
            validateInstallationDate(this);
        });

        installationDateInput.addEventListener('blur', function() {
            validateInstallationDate(this);
        });
    }

    // Installation Cost change
    if (installationCostInput) {
        installationCostInput.addEventListener('input', function() {
            validateCost(this);
            calculateTotalCost();
        });

        installationCostInput.addEventListener('blur', function() {
            validateCost(this);
        });
    }

    // Parts Cost change
    if (partsCostInput) {
        partsCostInput.addEventListener('input', function() {
            validateCost(this);
            calculateTotalCost();
        });

        partsCostInput.addEventListener('blur', function() {
            validateCost(this);
        });
    }

    // Status validation
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            validateStatus(this);
        });
    }

    // Warranty validation
    if (warrantyInput) {
        warrantyInput.addEventListener('input', function() {
            validateWarranty(this);
        });

        warrantyInput.addEventListener('blur', function() {
            validateWarranty(this);
        });
    }

    // ========== VALIDATION FUNCTIONS ==========
    
    function validateCarId(select) {
        const value = select.value.trim();
        const errorDiv = document.getElementById('car_id-error');
        
        if (value.length === 0) {
            setFieldState(select, 'invalid', 'Izbor automobila je obavezan', errorDiv);
            return false;
        } else {
            setFieldState(select, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateModType(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('mod_type-error');
        
        if (value.length === 0) {
            setFieldState(input, 'invalid', 'Tip modifikacije je obavezan', errorDiv);
            return false;
        } else if (value.length < 3) {
            setFieldState(input, 'invalid', 'Tip modifikacije mora imati najmanje 3 karaktera', errorDiv);
            return false;
        } else if (value.length > 100) {
            setFieldState(input, 'invalid', 'Tip modifikacije može imati maksimalno 100 karaktera', errorDiv);
            return false;
        } else {
            setFieldState(input, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateCategory(select) {
        const value = select.value.trim();
        const errorDiv = document.getElementById('category-error');
        
        if (value.length === 0) {
            setFieldState(select, 'invalid', 'Izbor kategorije je obavezan', errorDiv);
            return false;
        } else {
            setFieldState(select, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateInstallationDate(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('installation_date-error');
        
        if (value.length === 0) {
            setFieldState(input, 'invalid', 'Datum instalacije je obavezan', errorDiv);
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

    function validateCost(input) {
        const value = parseFloat(input.value);
        const errorDiv = document.getElementById(input.id + '-error');
        
        if (input.value === '') {
            // Empty is allowed for cost fields
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

    function validateStatus(select) {
        const value = select.value.trim();
        const errorDiv = document.getElementById('status-error');
        
        if (value.length === 0) {
            setFieldState(select, 'invalid', 'Izbor statusa je obavezan', errorDiv);
            return false;
        } else {
            setFieldState(select, 'valid', '', errorDiv);
            return true;
        }
    }

    function validateWarranty(input) {
        const value = parseInt(input.value);
        const errorDiv = document.getElementById('warranty-error');
        
        if (input.value === '') {
            // Empty is allowed for warranty
            setFieldState(input, 'valid', '', errorDiv);
            return true;
        }
        
        if (isNaN(value)) {
            setFieldState(input, 'invalid', 'Garantija mora biti broj', errorDiv);
            return false;
        } else if (value < 0) {
            setFieldState(input, 'invalid', 'Garantija ne može biti negativna', errorDiv);
            return false;
        } else if (value > 240) {
            setFieldState(input, 'invalid', 'Garantija ne može biti veća od 240 meseci', errorDiv);
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

    // ========== TOTAL COST CALCULATION ==========
    function calculateTotalCost() {
        const installation = parseFloat(installationCostInput?.value || 0) || 0;
        const parts = parseFloat(partsCostInput?.value || 0) || 0;
        const total = installation + parts;
        
        if (totalCostDisplay) {
            totalCostDisplay.value = total.toFixed(2);
        }
        
        console.log(`Installation: ${installation} RSD, Parts: ${parts} RSD, Total: ${total} RSD`);
    }

    // ========== FORM SUBMISSION ==========
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate all required fields
            const isModTypeValid = validateModType(modTypeInput);
            const isCategoryValid = validateCategory(categorySelect);
            const isInstallationDateValid = validateInstallationDate(installationDateInput);
            const isStatusValid = validateStatus(statusSelect);
            
            if (isModTypeValid && isCategoryValid && isInstallationDateValid && isStatusValid) {
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

    // ========== POPULAR MODIFICATIONS ==========
    const modButtons = document.querySelectorAll('.mod-btn');
    
    modButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const modName = this.getAttribute('data-mod');
            
            // Set modification type value
            if (modTypeInput) {
                modTypeInput.value = modName;
                modTypeInput.focus();
                validateModType(modTypeInput);
                
                // Visual feedback
                modButtons.forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
                
                // Scroll to form
                modTypeInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                showNotification(`Izabrana modifikacija: ${modName}`, 'success');
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
                totalCostDisplay.value = '0';
                modButtons.forEach(b => b.classList.remove('selected'));
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
        if (modTypeInput || categorySelect || installationDateInput) {
            const draft = {
                carId: carIdSelect?.value || '',
                modType: modTypeInput?.value || '',
                category: categorySelect?.value || '',
                description: descriptionTextarea?.value || '',
                installationDate: installationDateInput?.value || '',
                installationCost: installationCostInput?.value || '',
                partsCost: partsCostInput?.value || '',
                status: statusSelect?.value || '',
                warranty: warrantyInput?.value || '',
                notes: notesTextarea?.value || ''
            };
            
            localStorage.setItem('add_modification_draft', JSON.stringify(draft));
        }
    }

    // Auto-save every 5 seconds
    setInterval(saveDraft, 5000);

    // Restore draft on page load
    const savedDraft = localStorage.getItem('add_modification_draft');
    if (savedDraft) {
        try {
            const draft = JSON.parse(savedDraft);
            
            if (Object.values(draft).some(v => v)) {
                if (confirm('Pronađen je sačuvan draft. Želite li da ga učitate?')) {
                    if (carIdSelect && draft.carId) carIdSelect.value = draft.carId;
                    if (modTypeInput && draft.modType) modTypeInput.value = draft.modType;
                    if (categorySelect && draft.category) categorySelect.value = draft.category;
                    if (descriptionTextarea && draft.description) descriptionTextarea.value = draft.description;
                    if (installationDateInput && draft.installationDate) installationDateInput.value = draft.installationDate;
                    if (installationCostInput && draft.installationCost) installationCostInput.value = draft.installationCost;
                    if (partsCostInput && draft.partsCost) partsCostInput.value = draft.partsCost;
                    if (statusSelect && draft.status) statusSelect.value = draft.status;
                    if (warrantyInput && draft.warranty) warrantyInput.value = draft.warranty;
                    if (notesTextarea && draft.notes) notesTextarea.value = draft.notes;
                    
                    calculateTotalCost();
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
            localStorage.removeItem('add_modification_draft');
        });
    }

    // ========== CONSOLE EASTER EGG ==========
    console.log('%c🔧 Add Modification Form', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
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
        const requiredFields = 5; // car_id, mod_type, category, installation_date, status
        
        if (carIdSelect && carIdSelect.value.trim()) filledFields++;
        if (modTypeInput && modTypeInput.value.trim()) filledFields++;
        if (categorySelect && categorySelect.value.trim()) filledFields++;
        if (installationDateInput && installationDateInput.value.trim()) filledFields++;
        if (statusSelect && statusSelect.value.trim()) filledFields++;
        
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