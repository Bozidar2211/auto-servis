/**
 * REPLY FORM PAGE JAVASCRIPT
 * Mehaničarova forma za odgovor na zahtjev
 */

document.addEventListener('DOMContentLoaded', () => {

    // ========== FORM REFERENCES ==========
    const form = document.getElementById('replyForm');
    console.log('Form element:', form);
    const priceInput = document.getElementById('price');
    const dateInput = document.getElementById('date');
    const noteInput = document.getElementById('note');
    const acceptTermsInput = document.querySelector('input[name="accept_terms"]');
    const submitBtn = document.querySelector('.btn-submit');
     console.log('Submit button found:', submitBtn);

    // ========== SET MINIMUM DATE ==========
    const today = new Date();
    today.setDate(today.getDate() + 1);
    dateInput.min = today.toISOString().split('T')[0];

    // ========== CHARACTER COUNTER ==========
    const charCounter = document.getElementById('charCounter');
    const maxChars = 1000;

    if (noteInput && charCounter) {
  noteInput.addEventListener('input', (e) => {
    const length = e.target.value.length;
    charCounter.textContent = Math.min(length, 500);

    if (length > 500) {
      e.target.value = e.target.value.substring(0, 500);
    }

    updatePreview();
  });
}

    // ========== REAL-TIME PREVIEW UPDATE ==========
    priceInput.addEventListener('input', updatePreview);
    dateInput.addEventListener('change', updatePreview);

    function updatePreview() {

        // Note / Napomena
document.getElementById('previewNote').textContent = noteInput.value.trim() 
    ? noteInput.value.trim() 
    : '-';


        // Price
const priceValue = priceInput.value.trim();
const price = priceValue ? parseFloat(priceValue) : null;

document.getElementById('previewPrice').textContent = price !== null && !isNaN(price)
    ? price.toFixed(2) + ' RSD'
    : '-';

        // Date
        const date = dateInput.value;
        document.getElementById('previewDate').textContent = date 
            ? formatDate(date) 
            : '-';

        // Note
        const note = noteInput.value.trim();
document.getElementById('previewNote').textContent = note || '-';


        // Status
        updateFormStatus();
    }

    function updateFormStatus() {
        const isComplete = priceInput.value && 
                          dateInput.value && 
                          noteInput.value.trim().length > 10;
        
        const statusElement = document.getElementById('previewStatus');
        if (isComplete) {
            statusElement.textContent = 'Gotovo';
            statusElement.classList.add('complete');
            submitBtn.disabled = false;
        } else {
            statusElement.textContent = 'Nepotpuno';
            statusElement.classList.remove('complete');
            submitBtn.disabled = true;
        }
    }

    // ========== HELPER FUNCTIONS ==========
    function formatPrice(price) {
        return new Intl.NumberFormat('sr-RS', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(price);
    }

    function formatDate(dateString) {
        const date = new Date(dateString + 'T00:00:00');
        return new Intl.DateTimeFormat('sr-RS', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        }).format(date);
    }

    // PRICE INPUT FORMATTING
function formatPrice(value) {
    return !isNaN(value) ? value.toFixed(2) : '';
}

priceInput.addEventListener('blur', function() {
    if (this.value) {
        const price = parseFloat(this.value);
        this.value = formatPrice(price);
    }
});


    // ========== FORM SUBMIT ==========
    console.log('reply_form.js loaded');
form.addEventListener('submit', function(e) {
    console.log('✅ Submit triggered');

    e.preventDefault();

    // Validacija
    if (!priceInput.value.trim() || !dateInput.value.trim() || !noteInput.value.trim()) {
        showNotification('Molimo popunite sva obavezna polja', 'error');
        return;
    }

    // Loading state
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Slanje...';
    submitBtn.disabled = true;

    // FormData
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Ponuda je uspješno poslana!', 'success');
            setTimeout(() => {
                window.location.href = '/auto-servis/mechanic.php?action=dashboard';
            }, 2000);
        } else {
            showNotification(data.message || 'Greška pri slanju ponude', 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Greška pri slanju ponude', 'error');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
    console.log('🚀 Submit handler attached');
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

    // ========== NOTIFICATION ANIMATIONS ==========
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

    // ========== SCROLL TO TOP ==========
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

    /*// ========== NAVBAR SCROLL EFFECT ==========
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 50) {
            navbar.style.boxShadow = '0 8px 30px rgba(0, 0, 0, 0.8)';
        } else {
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.5)';
        }
    });*/

    // ========== FORM CHANGE WARNING ==========
    let formModified = false;

    form.addEventListener('change', () => {
        formModified = true;
    });

    form.addEventListener('input', () => {
        formModified = true;
    });

    window.addEventListener('beforeunload', (e) => {
        if (formModified && !form.classList.contains('submitted')) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // ========== FORM SUBMITTED CLASS ==========
    form.addEventListener('submit', () => {
        form.classList.add('submitted');
    });

    // ========== PAGE VISIBILITY ==========
    document.addEventListener('visibilitychange', () => {
        const animatedBg = document.querySelector('.animated-bg');
        
        if (document.hidden) {
            if (animatedBg) animatedBg.style.animationPlayState = 'paused';
        } else {
            if (animatedBg) animatedBg.style.animationPlayState = 'running';
        }
    });

    // ========== CONSOLE MESSAGE ==========
    console.log('%c🔧 Reply Form Page', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
    console.log('%cMehaničarova forma za odgovore', 'color: #28a745; font-weight: bold;');
    console.log('Live preview i validacija aktivni');

    // ========== INITIAL PREVIEW UPDATE ==========
    updatePreview();

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