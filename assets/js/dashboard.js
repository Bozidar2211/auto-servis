document.addEventListener('DOMContentLoaded', () => {
    // ========== SCROLL ANIMATIONS ==========
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all cards and sections
    document.querySelectorAll('.car-card, .action-card, .section-header').forEach(el => {
        observer.observe(el);
    });

    // ========== SMOOTH SCROLL ==========
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ========== CAR CARD HOVER EFFECTS ==========
    const carCards = document.querySelectorAll('.car-card');
    
    carCards.forEach(card => {
        card.addEventListener('mouseenter', function(e) {
            const icon = this.querySelector('.car-icon');
            if (icon) {
                icon.style.transform = 'scale(1.1) rotate(5deg)';
            }
        });
        
        card.addEventListener('mouseleave', function(e) {
            const icon = this.querySelector('.car-icon');
            if (icon) {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }
        });

        // 3D tilt effect on mouse move
        card.addEventListener('mousemove', function(e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-5px)`;
        });
        
        card.addEventListener('mouseleave', function() {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
        });
    });

    // ========== ACTION BUTTONS RIPPLE EFFECT ==========
    const actionButtons = document.querySelectorAll('.action-btn, .btn-primary-custom');
    
    actionButtons.forEach(button => {
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

    // ========== REMINDER ANIMATIONS ==========
    const reminderItems = document.querySelectorAll('.reminder-item');
    
    reminderItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
        item.classList.add('slide-in-right');
    });

    // ========== DELETE CONFIRMATION WITH ANIMATION ==========
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            // Show custom modal instead of browser confirm
            showConfirmModal(
                this.getAttribute('data-confirm'),
                () => {
                    // Confirmed - add loading animation
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    button.disabled = true;
                    
                    // Submit after animation
                    setTimeout(() => {
                        this.submit();
                    }, 500);
                },
                () => {
                    // Cancelled - shake the button
                    button.style.animation = 'shake 0.5s';
                    setTimeout(() => {
                        button.style.animation = '';
                    }, 500);
                }
            );
        });
    });

    // ========== CUSTOM CONFIRM MODAL ==========
    function showConfirmModal(message, onConfirm, onCancel) {
        // Create modal overlay
        const overlay = document.createElement('div');
        overlay.className = 'confirm-overlay';
        
        const modal = document.createElement('div');
        modal.className = 'confirm-modal';
        modal.innerHTML = `
            <div class="confirm-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h5>Potvrda</h5>
            <p>${message}</p>
            <div class="confirm-actions">
                <button class="btn-cancel">Otkaži</button>
                <button class="btn-confirm">Potvrdi</button>
            </div>
        `;
        
        overlay.appendChild(modal);
        document.body.appendChild(overlay);
        
        // Animate in
        setTimeout(() => {
            overlay.classList.add('active');
            modal.classList.add('active');
        }, 10);
        
        // Handle confirm
        modal.querySelector('.btn-confirm').addEventListener('click', () => {
            closeModal(overlay);
            onConfirm();
        });
        
        // Handle cancel
        modal.querySelector('.btn-cancel').addEventListener('click', () => {
            closeModal(overlay);
            if (onCancel) onCancel();
        });
        
        // Close on overlay click
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                closeModal(overlay);
                if (onCancel) onCancel();
            }
        });
    }

    function closeModal(overlay) {
        overlay.classList.remove('active');
        overlay.querySelector('.confirm-modal').classList.remove('active');
        setTimeout(() => overlay.remove(), 300);
    }

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

    // ========== LOADING STATE FOR LINKS ==========
    document.querySelectorAll('.action-btn, .btn-primary-custom').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.classList.contains('no-loading')) {
                const icon = this.querySelector('i');
                if (icon && !icon.classList.contains('fa-spin')) {
                    const originalIcon = icon.className;
                    icon.className = 'fas fa-spinner fa-spin';
                    
                    // Restore icon if navigation doesn't happen
                    setTimeout(() => {
                        icon.className = originalIcon;
                    }, 2000);
                }
            }
        });
    });

    // ========== QUICK STATS COUNTER ANIMATION ==========
    function animateCounter(element, target, duration = 1000) {
        let start = 0;
        const increment = target / (duration / 16);
        
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(start);
            }
        }, 16);
    }

    // Animate any counters on page
    document.querySelectorAll('[data-count]').forEach(counter => {
        const target = parseInt(counter.getAttribute('data-count'));
        animateCounter(counter, target);
    });

    // ========== HEADER SCROLL EFFECT ==========
    let lastScroll = 0;
    const header = document.querySelector('.modern-header');
    
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            header.style.boxShadow = '0 8px 30px rgba(0, 0, 0, 0.8)';
        } else {
            header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.5)';
        }
        
        lastScroll = currentScroll;
    });

    // ========== TOOLTIP SYSTEM ==========
    document.querySelectorAll('[title]').forEach(element => {
        const title = element.getAttribute('title');
        element.removeAttribute('title');
        element.setAttribute('data-tooltip', title);
        
        element.addEventListener('mouseenter', function(e) {
            const tooltip = document.createElement('div');
            tooltip.className = 'custom-tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
            
            setTimeout(() => tooltip.classList.add('active'), 10);
            
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.classList.remove('active');
                setTimeout(() => this._tooltip.remove(), 300);
            }
        });
    });

    // ========== KEYBOARD SHORTCUTS ==========
    document.addEventListener('keydown', (e) => {
        // Alt + N = New car
        if (e.altKey && e.key === 'n') {
            e.preventDefault();
            window.location.href = 'add_car.php';
        }
        
        // Alt + R = New request
        if (e.altKey && e.key === 'r') {
            e.preventDefault();
            window.location.href = '/auto-servis/user.php?controller=request&action=showForm';
        }
        
        // Escape = Close any open modals
        if (e.key === 'Escape') {
            const overlay = document.querySelector('.confirm-overlay');
            if (overlay) {
                closeModal(overlay);
            }
        }
    });

    // ========== CONSOLE EASTER EGG ==========
    console.log('%c🚗 Auto Servis Dashboard', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
    console.log('%cKeyboard shortcuts:\nAlt + N: Dodaj vozilo\nAlt + R: Novi zahtev', 'color: #888;');
    
    // ========== CAR SEARCH/FILTER ==========
    window.filterCars = function(query) {
        const cars = document.querySelectorAll('.car-card');
        const lowerQuery = query.toLowerCase();
        
        cars.forEach(card => {
            const carInfo = card.querySelector('.car-info h5').textContent.toLowerCase();
            const carMeta = card.querySelector('.car-meta').textContent.toLowerCase();
            
            if (carInfo.includes(lowerQuery) || carMeta.includes(lowerQuery)) {
                card.style.display = 'block';
                card.style.animation = 'fadeIn 0.5s ease-out';
            } else {
                card.style.opacity = '0';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
    };
    
    // ========== WELCOME BANNER ANIMATION ==========
    const welcomeBanner = document.querySelector('.welcome-banner');
    if (welcomeBanner) {
        welcomeBanner.style.animation = 'slideInDown 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
    }
    
    // ========== STAT NUMBERS ANIMATION ==========
    document.querySelectorAll('.stat-number').forEach(stat => {
        const target = parseInt(stat.textContent);
        if (!isNaN(target)) {
            stat.textContent = '0';
            animateCounter(stat, target, 1500);
        }
    });

});