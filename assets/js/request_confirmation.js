/**
 * REQUEST CONFIRMATION PAGE JAVASCRIPT
 * Animacije i interaktivne funkcije
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

    // ========== BUTTON INTERACTIONS ==========
    const actionButtons = document.querySelectorAll('.action-buttons .btn');
    
    actionButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });

        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // ========== DETAIL ITEMS ANIMATION ==========
    const detailItems = document.querySelectorAll('.detail-item');
    
    detailItems.forEach((item, index) => {
        item.style.animation = `fadeInUp 0.6s ease-out ${index * 0.1}s backwards`;
    });

    // ========== FLOATING CARDS ANIMATION ==========
    const floatingCards = document.querySelectorAll('.float-card');
    
    floatingCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 1}s`;
    });

    // ========== NEXT STEPS ANIMATION ==========
    const nextSteps = document.querySelectorAll('.next-steps li');
    
    nextSteps.forEach((step, index) => {
        step.style.animation = `slideInLeft 0.6s ease-out ${0.3 + index * 0.1}s backwards`;
    });

    // ========== COPY SUCCESS MESSAGE ==========
    const confirmationCard = document.querySelector('.confirmation-card');
    
    confirmationCard.addEventListener('click', function(e) {
        // Create ripple effect
        const ripple = document.createElement('span');
        ripple.className = 'ripple';
        
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        
        // Add ripple animation style if not already present
        if (!document.querySelector('#rippleStyles')) {
            const style = document.createElement('style');
            style.id = 'rippleStyles';
            style.textContent = `
                .ripple {
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(240, 173, 78, 0.6);
                    transform: scale(0);
                    animation: rippleAnimation 0.6s ease-out;
                    pointer-events: none;
                }

                @keyframes rippleAnimation {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        confirmationCard.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
    });

    // ========== PAGE LOAD CONFETTI ==========
    function createConfetti() {
        const confettiPieces = [];
        const colors = ['#f0ad4e', '#d98c00', '#28a745', '#17a2b8'];
        
        for (let i = 0; i < 30; i++) {
            const confetti = document.createElement('div');
            confetti.style.cssText = `
                position: fixed;
                width: 10px;
                height: 10px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                left: ${Math.random() * window.innerWidth}px;
                top: -10px;
                opacity: 0.8;
                pointer-events: none;
                border-radius: ${Math.random() > 0.5 ? '50%' : '0'};
                z-index: 10000;
            `;
            
            document.body.appendChild(confetti);
            confettiPieces.push(confetti);
            
            animateConfetti(confetti);
        }
    }

    function animateConfetti(confetti) {
        let top = -10;
        let left = parseFloat(confetti.style.left);
        let rotation = Math.random() * 360;
        const xVelocity = (Math.random() - 0.5) * 8;
        const duration = 3000 + Math.random() * 2000;
        const startTime = Date.now();

        function animate() {
            const elapsed = Date.now() - startTime;
            const progress = elapsed / duration;

            if (progress < 1) {
                top = -10 + progress * (window.innerHeight + 20);
                left += xVelocity * 0.5;
                rotation += 5;

                confetti.style.top = top + 'px';
                confetti.style.left = left + 'px';
                confetti.style.transform = `rotate(${rotation}deg)`;
                confetti.style.opacity = Math.max(0, 0.8 - progress * 0.8);

                requestAnimationFrame(animate);
            } else {
                confetti.remove();
            }
        }

        animate();
    }

    // Create confetti on page load with delay
    setTimeout(() => {
        createConfetti();
    }, 1000);

    // ========== SOUND EFFECT (optional) ==========
    function playSuccessSound() {
        // Simple beep using Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.frequency.value = 800;
        oscillator.type = 'sine';

        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.5);
    }

    // Try to play success sound
    try {
        playSuccessSound();
    } catch (e) {
        console.log('Audio not available');
    }

    // ========== ADD KEYBOARD NAVIGATION ==========
    document.addEventListener('keydown', (e) => {
        const buttons = document.querySelectorAll('.action-buttons .btn');
        
        if (e.key === 'Tab') {
            buttons.forEach(btn => {
                btn.addEventListener('focus', function() {
                    this.style.outline = '2px solid var(--primary)';
                    this.style.outlineOffset = '2px';
                });

                btn.addEventListener('blur', function() {
                    this.style.outline = 'none';
                });
            });
        }
    });

    // ========== DETAIL ITEMS HOVER ==========
    const detailItems2 = document.querySelectorAll('.detail-item');
    
    detailItems2.forEach(item => {
        item.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.detail-icon');
            icon.style.transform = 'scale(1.1) rotate(10deg)';
        });

        item.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.detail-icon');
            icon.style.transform = 'scale(1) rotate(0)';
        });
    });

    // ========== PROGRESS BAR (optional) ==========
    const progressBar = document.createElement('div');
    progressBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #f0ad4e, #d98c00);
        width: 0;
        z-index: 10001;
        animation: progressLoad 2s ease-out forwards;
    `;
    
    if (!document.querySelector('#progressBar')) {
        progressBar.id = 'progressBar';
        document.body.appendChild(progressBar);

        const style = document.createElement('style');
        style.textContent = `
            @keyframes progressLoad {
                0% { width: 0; }
                100% { width: 100%; }
            }
        `;
        document.head.appendChild(style);
    }

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
    console.log('%c✅ Request Confirmation Page', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
    console.log('%cKvadratni zahtev je uspešno poslat!', 'color: #28a745; font-weight: bold;');
    console.log('%cRedirekcija će biti dostupna u sledećim linijama.', 'color: #888;');

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