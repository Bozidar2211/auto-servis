/**
 * UPCOMING REMINDERS PAGE JAVASCRIPT
 * Timeline functionality i interaktivne akcije
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

    // ========== ANIMATE COUNTERS ==========
    const statNumbers = document.querySelectorAll('.stat-number');
    
    statNumbers.forEach((stat, index) => {
        const finalValue = parseInt(stat.textContent);
        const delay = index * 100;
        
        setTimeout(() => {
            let current = 0;
            const step = Math.ceil(finalValue / 20);
            
            const interval = setInterval(() => {
                current += step;
                if (current >= finalValue) {
                    current = finalValue;
                    clearInterval(interval);
                }
                stat.textContent = current;
            }, 30);
        }, delay);
    });

    // ========== CALCULATE DAYS LEFT ==========
    function calculateDaysLeft() {
        const daysLeftElements = document.querySelectorAll('[data-date]');
        
        daysLeftElements.forEach(element => {
            const reminderDate = element.getAttribute('data-date');
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            const reminder = new Date(reminderDate);
            reminder.setHours(0, 0, 0, 0);
            
            const diffTime = reminder - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 0) {
                element.textContent = diffDays;
            } else if (diffDays === 0) {
                element.textContent = '0';
            } else {
                element.textContent = Math.abs(diffDays);
            }
        });
    }

    calculateDaysLeft();

    // ========== DELETE REMINDER ==========
    const deleteButtons = document.querySelectorAll('.btn-action.delete');
    
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const reminderId = this.getAttribute('data-id');
            
            if (confirm('Da li ste sigurni da želite da obrišete ovaj podsetnik?')) {
                fetch('../controllers/DeleteController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'type=reminder&id=' + reminderId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const reminderItem = btn.closest('.reminder-item');
                        reminderItem.style.animation = 'fadeOutDown 0.4s ease-out';
                        
                        setTimeout(() => {
                            reminderItem.remove();
                            
                            // Check if section is empty
                            const section = btn.closest('.timeline-section');
                            const itemsLeft = section.querySelectorAll('.reminder-item');
                            
                            if (itemsLeft.length === 0) {
                                section.style.animation = 'fadeOutDown 0.4s ease-out';
                                setTimeout(() => {
                                    section.remove();
                                    location.reload();
                                }, 400);
                            }
                            
                            showNotification('Podsetnik je obrisan', 'success');
                        }, 400);
                    } else {
                        showNotification('Greška pri brisanju podsetnika', 'error');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showNotification('Greška pri brisanju podsetnika', 'error');
                });
            }
        });
    });

    // ========== CARD ANIMATIONS ON LOAD ==========
    const reminderItems = document.querySelectorAll('.reminder-item');
    
    reminderItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.05}s`;
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

    // Add notification animations if not present
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

    // ========== TIMELINE SECTION ANIMATIONS ==========
    const timelineSections = document.querySelectorAll('.timeline-section');
    
    timelineSections.forEach((section, index) => {
        section.style.animationDelay = `${index * 0.1}s`;
        
        // Add hover effect to section
        section.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        
        section.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // ========== SMOOTH SCROLL TO SECTION ==========
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ========== KEYBOARD SHORTCUTS ==========
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + R = Refresh reminders
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            location.reload();
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

    // ========== CONSOLE EASTER EGG ==========
    console.log('%c📅 Upcoming Reminders Page', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
    console.log('%cKeyboard shortcuts:', 'color: #888; font-weight: bold;');
    console.log('Ctrl/Cmd + R: Refresh reminders');
    console.log('%cTotal reminders loaded:', 'color: #28a745;', reminderItems.length);

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