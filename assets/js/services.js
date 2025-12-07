/**
 * SERVICES PAGE JAVASCRIPT
 * Interaktivne funkcionalnosti i animacije
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
const header = document.querySelector('.services-header');

window.addEventListener('scroll', () => {
    if (window.pageYOffset > 50) {
        header.style.boxShadow = '0 8px 30px rgba(0, 0, 0, 0.8)';
    } else {
        header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.5)';
    }
});

// ========== SEARCH FUNCTIONALITY ==========
const searchInput = document.getElementById('searchServices');

if (searchInput) {
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const serviceCards = document.querySelectorAll('.service-card');
        
        serviceCards.forEach(card => {
            const description = card.querySelector('.service-description p').textContent.toLowerCase();
            const date = card.querySelector('.service-date span').textContent.toLowerCase();
            const cost = card.querySelector('.service-cost').textContent.toLowerCase();
            
            if (description.includes(searchTerm) || 
                date.includes(searchTerm) || 
                cost.includes(searchTerm)) {
                card.style.display = 'block';
                card.style.animation = 'fadeInUp 0.5s ease-out';
            } else {
                card.style.display = 'none';
            }
        });
    });
}

// ========== DELETE CONFIRMATION ==========
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const serviceName = this.getAttribute('data-service-name');
        
        // Kreiranje custom modala za potvrdu
        showConfirmModal(
            `Da li ste sigurni da želite da obrišete servis "${serviceName}"?`,
            () => {
                // Potvrđeno - submit forme
                this.submit();
            }
        );
    });
});

// ========== CUSTOM CONFIRM MODAL ==========
function showConfirmModal(message, onConfirm) {
    const overlay = document.createElement('div');
    overlay.className = 'confirm-overlay';
    
    const modal = document.createElement('div');
    modal.className = 'confirm-modal';
    modal.innerHTML = `
        <div class="confirm-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h5>Potvrda brisanja</h5>
        <p>${message}</p>
        <div class="confirm-actions">
            <button class="btn-cancel">Otkaži</button>
            <button class="btn-confirm">Obriši</button>
        </div>
    `;
    
    overlay.appendChild(modal);
    document.body.appendChild(overlay);
    
    setTimeout(() => {
        overlay.classList.add('active');
        modal.classList.add('active');
    }, 10);
    
    modal.querySelector('.btn-confirm').addEventListener('click', () => {
        closeModal(overlay);
        onConfirm();
    });
    
    modal.querySelector('.btn-cancel').addEventListener('click', () => {
        closeModal(overlay);
    });
    
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            closeModal(overlay);
        }
    });
}

function closeModal(overlay) {
    overlay.classList.remove('active');
    overlay.querySelector('.confirm-modal').classList.remove('active');
    setTimeout(() => overlay.remove(), 300);
}

// ========== STATS COUNTER ANIMATION ==========
function animateCounter(element, target, duration = 1500) {
    let start = 0;
    const increment = target / (duration / 16);
    
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = Math.floor(target);
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(start);
        }
    }, 16);
}

document.querySelectorAll('.stat-number[data-count]').forEach(counter => {
    const target = parseInt(counter.getAttribute('data-count'));
    if (!isNaN(target)) {
        counter.textContent = '0';
        setTimeout(() => {
            animateCounter(counter, target);
        }, 300);
    }
});

// ========== SERVICE CARDS HOVER EFFECT ==========
document.querySelectorAll('.service-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        const icon = this.querySelector('.service-description i');
        if (icon) {
            icon.style.transform = 'scale(1.2) rotate(10deg)';
        }
    });
    
    card.addEventListener('mouseleave', function() {
        const icon = this.querySelector('.service-description i');
        if (icon) {
            icon.style.transform = 'scale(1) rotate(0deg)';
        }
    });
});

// ========== RIPPLE EFFECT ON BUTTONS ==========
document.querySelectorAll('.service-btn, .action-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');
        
        this.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
    });
});

// ========== KEYBOARD SHORTCUTS ==========
document.addEventListener('keydown', (e) => {
    // Ctrl/Cmd + F = Focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.getElementById('searchServices');
        if (searchInput) {
            searchInput.focus();
        }
    }
    
    // Escape = Clear search
    if (e.key === 'Escape') {
        const searchInput = document.getElementById('searchServices');
        if (searchInput && searchInput.value !== '') {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
        }
    }
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
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.classList.add('active'), 10);
    
    setTimeout(() => {
        notification.classList.remove('active');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
};

// ========== CHECK URL PARAMETERS FOR NOTIFICATIONS ==========
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('success') === '1') {
    showNotification('Servis je uspešno dodat!', 'success');
}
if (urlParams.get('deleted') === '1') {
    showNotification('Servis je uspešno obrisan!', 'success');
}

// ========== CONSOLE EASTER EGG ==========
console.log('%c🔧 Servisna Istorija', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
console.log('%cKeyboard shortcuts:\nCtrl+F: Search\nEscape: Clear search', 'color: #888;');
});
// ========== INJECT MODAL & NOTIFICATION STYLES ==========
if (!document.getElementById('servicesCustomStyles')) {
const style = document.createElement('style');
style.id = 'servicesCustomStyles';
style.textContent = `
/* Ripple Effect */
.ripple {
position: absolute;
border-radius: 50%;
background: rgba(255, 255, 255, 0.5);
transform: scale(0);
animation: rippleEffect 0.6s ease-out;
pointer-events: none;
}
    @keyframes rippleEffect {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    /* Confirm Modal */
    .confirm-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .confirm-overlay.active {
        opacity: 1;
    }

    .confirm-modal {
        background: linear-gradient(135deg, rgba(30, 30, 30, 0.98), rgba(20, 20, 20, 0.98));
        border: 1px solid rgba(240, 173, 78, 0.3);
        border-radius: 16px;
        padding: 2rem;
        max-width: 400px;
        width: 90%;
        text-align: center;
        transform: scale(0.7);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .confirm-modal.active {
        transform: scale(1);
        opacity: 1;
    }

    .confirm-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        background: rgba(255, 193, 7, 0.2);
        border: 2px solid #ffc107;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #ffc107;
    }

    .confirm-modal h5 {
        color: var(--text-light);
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .confirm-modal p {
        color: var(--text-muted);
        margin-bottom: 1.5rem;
    }

    .confirm-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .btn-cancel,
    .btn-confirm {
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-light);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-cancel:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }

    .btn-confirm {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
    }

    /* Notification */
    .notification {
        position: fixed;
        top: 20px;
        right: -400px;
        background: rgba(30, 30, 30, 0.98);
        border-left: 4px solid var(--primary-color);
        border-radius: 10px;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8);
        z-index: 10000;
        transition: right 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        min-width: 300px;
    }

    .notification.active {
        right: 20px;
    }

    .notification i {
        font-size: 1.5rem;
    }

    .notification-success {
        border-left-color: #28a745;
    }

    .notification-success i {
        color: #28a745;
    }

    .notification span {
        color: var(--text-light);
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .notification {
            left: 10px;
            right: 10px;
            min-width: auto;
        }
        
        .notification.active {
            right: 10px;
        }
        
        .confirm-modal {
            width: 95%;
            padding: 1.5rem;
        }
        
        .confirm-actions {
            flex-direction: column;
        }
        
        .btn-cancel,
        .btn-confirm {
            width: 100%;
        }
    }
`;
document.head.appendChild(style);
}