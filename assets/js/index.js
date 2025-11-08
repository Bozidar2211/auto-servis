/**
 * LANDING PAGE JAVASCRIPT - AUTO SERVIS
 * Sve animacije, efekti i interakcije
 */

document.addEventListener('DOMContentLoaded', () => {
    
    // ========== COUNTER ANIMATION ==========
    const animateCounter = (element, target, duration = 2000) => {
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
    };
  
    // Intersection Observer for counters
    const counterObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const target = parseInt(entry.target.getAttribute('data-count'));
          animateCounter(entry.target, target);
          counterObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
  
    document.querySelectorAll('[data-count]').forEach(counter => {
      counterObserver.observe(counter);
    });
  
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
  
    // ========== SMOOTH SCROLL FOR LINKS ==========
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
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
  
    // ========== NAVBAR SCROLL EFFECT ==========
    const navbar = document.querySelector('.navbar');
    let lastScroll = 0;
  
    window.addEventListener('scroll', () => {
      const currentScroll = window.pageYOffset;
      
      if (currentScroll > 100) {
        navbar.style.boxShadow = '0 8px 30px rgba(0, 0, 0, 0.8)';
      } else {
        navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.5)';
      }
      
      lastScroll = currentScroll;
    });
  
    // ========== PARALLAX EFFECT FOR HERO ==========
    window.addEventListener('scroll', () => {
      const scrolled = window.pageYOffset;
      const heroContent = document.querySelector('.hero-content');
      if (heroContent && scrolled < window.innerHeight) {
        heroContent.style.transform = `translateY(${scrolled * 0.5}px)`;
        heroContent.style.opacity = 1 - (scrolled / 600);
      }
    });
  
    // ========== FEATURE CARDS REVEAL ANIMATION ==========
    const featureObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          setTimeout(() => {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }, index * 100);
          featureObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });
  
    document.querySelectorAll('.feature-card').forEach((card, index) => {
      featureObserver.observe(card);
    });
  
    // ========== LOCATION CARDS REVEAL ==========
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });
  
    document.querySelectorAll('.location-card, .stat-item').forEach(element => {
      revealObserver.observe(element);
    });
  
    // ========== FEATURE CARD 3D TILT EFFECT ==========
    document.querySelectorAll('.feature-card').forEach(card => {
      card.addEventListener('mousemove', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        const rotateX = (y - centerY) / 20;
        const rotateY = (centerX - x) / 20;
        
        this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
      });
    });
  
    // ========== BUTTON RIPPLE EFFECT ==========
    document.querySelectorAll('.btn-hero, .btn-login, .btn-register').forEach(button => {
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
        
        this.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
      });
    });
  
    // ========== TYPING EFFECT FOR HERO SUBTITLE ==========
    const subtitle = document.querySelector('.hero-subtitle');
    if (subtitle) {
      const text = subtitle.textContent;
      subtitle.textContent = '';
      
      let i = 0;
      const typeWriter = () => {
        if (i < text.length) {
          subtitle.textContent += text.charAt(i);
          i++;
          setTimeout(typeWriter, 50);
        }
      };
      
      setTimeout(typeWriter, 500);
    }
  
    // ========== RANDOM PARTICLE EFFECT ==========
    const createParticle = () => {
      const particle = document.createElement('div');
      particle.style.position = 'fixed';
      particle.style.width = '2px';
      particle.style.height = '2px';
      particle.style.background = 'rgba(240, 173, 78, 0.5)';
      particle.style.borderRadius = '50%';
      particle.style.pointerEvents = 'none';
      particle.style.zIndex = '1';
      
      const x = Math.random() * window.innerWidth;
      const y = Math.random() * window.innerHeight;
      
      particle.style.left = x + 'px';
      particle.style.top = y + 'px';
      
      document.body.appendChild(particle);
      
      particle.animate([
        { transform: 'translate(0, 0)', opacity: 1 },
        { transform: `translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px)`, opacity: 0 }
      ], {
        duration: 2000,
        easing: 'ease-out'
      }).onfinish = () => particle.remove();
    };
  
    // Create particles periodically
    const particleInterval = setInterval(createParticle, 300);
  
    // Stop particles after 30 seconds to save performance
    setTimeout(() => {
      clearInterval(particleInterval);
    }, 30000);
  
    // ========== NAVBAR MOBILE MENU AUTO CLOSE ==========
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        const navbarToggler = document.querySelector('.navbar-toggler');
        const navbarCollapse = document.querySelector('.navbar-collapse');
        
        if (navbarCollapse && navbarCollapse.classList.contains('show')) {
          navbarToggler.click();
        }
      });
    });
  
    // ========== LOADING ANIMATION FOR CTA BUTTONS ==========
    document.querySelectorAll('.btn-hero-primary').forEach(btn => {
      btn.addEventListener('click', function(e) {
        const icon = this.querySelector('i');
        if (icon) {
          const originalClass = icon.className;
          icon.className = 'fas fa-spinner fa-spin me-2';
          
          // Restore after navigation or timeout
          setTimeout(() => {
            icon.className = originalClass;
          }, 1500);
        }
      });
    });
  
    // ========== EASTER EGG - KONAMI CODE ==========
    const konamiCode = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
    let konamiIndex = 0;
  
    document.addEventListener('keydown', (e) => {
      if (e.key === konamiCode[konamiIndex]) {
        konamiIndex++;
        if (konamiIndex === konamiCode.length) {
          // Rainbow effect
          document.body.style.animation = 'rainbow 2s linear infinite';
          
          // Show notification
          showEasterEggNotification();
          
          setTimeout(() => {
            document.body.style.animation = '';
            konamiIndex = 0;
          }, 5000);
        }
      } else {
        konamiIndex = 0;
      }
    });
  
    // ========== EASTER EGG NOTIFICATION ==========
    function showEasterEggNotification() {
      const notification = document.createElement('div');
      notification.style.position = 'fixed';
      notification.style.top = '50%';
      notification.style.left = '50%';
      notification.style.transform = 'translate(-50%, -50%)';
      notification.style.background = 'rgba(240, 173, 78, 0.95)';
      notification.style.color = '#111';
      notification.style.padding = '2rem 3rem';
      notification.style.borderRadius = '20px';
      notification.style.fontSize = '1.5rem';
      notification.style.fontWeight = 'bold';
      notification.style.zIndex = '10000';
      notification.style.boxShadow = '0 10px 50px rgba(240, 173, 78, 0.5)';
      notification.textContent = '🎉 Konami Code Activated! 🎉';
      
      document.body.appendChild(notification);
      
      setTimeout(() => {
        notification.style.transition = 'opacity 0.5s ease';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 500);
      }, 2000);
    }
  
    // ========== PERFORMANCE OPTIMIZATION ==========
    // Pause animations when tab is not visible
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) {
        // Pause heavy animations
        document.querySelectorAll('.animated-bg, .feature-bg').forEach(el => {
          el.style.animationPlayState = 'paused';
        });
      } else {
        // Resume animations
        document.querySelectorAll('.animated-bg, .feature-bg').forEach(el => {
          el.style.animationPlayState = 'running';
        });
      }
    });
  
    // ========== CONSOLE EASTER EGG ==========
    console.log('%c🚗 Auto Servis Premium', 'font-size: 24px; font-weight: bold; color: #f0ad4e; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);');
    console.log('%cTražiš skrivene funkcije? Pokušaj Konami Code! 😉', 'color: #888; font-style: italic;');
    console.log('%c↑ ↑ ↓ ↓ ← → ← → B A', 'color: #f0ad4e; font-weight: bold;');
    console.log('%c\nDeveloper Info:', 'color: #f0ad4e; font-weight: bold;');
    console.log('Built with ❤️ by Božidar');
    console.log('Stack: PHP, MySQL, JavaScript, Bootstrap');
  
  });