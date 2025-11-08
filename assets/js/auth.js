/**
 * AUTH PAGES JAVASCRIPT - LOGIN & REGISTER
 * Validacija, animacije i interakcije
 */

document.addEventListener('DOMContentLoaded', () => {

  // ========== PASSWORD TOGGLE ==========
  window.togglePassword = function(inputId = 'password') {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling?.querySelector('i');
    
    if (!input || !icon) return;
    
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  };

  // ========== PASSWORD STRENGTH CHECKER ==========
  const passwordInput = document.getElementById('password');
  const strengthBar = document.getElementById('passwordStrength');
  
  if (passwordInput && strengthBar) {
    passwordInput.addEventListener('input', function() {
      const password = this.value;
      let strength = 0;
      
      // Calculate strength
      if (password.length >= 6) strength++;
      if (password.length >= 10) strength++;
      if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
      if (/\d/.test(password)) strength++;
      if (/[^a-zA-Z0-9]/.test(password)) strength++;
      
      // Update visual
      strengthBar.className = 'password-strength';
      if (strength <= 2) {
        strengthBar.classList.add('weak');
      } else if (strength <= 3) {
        strengthBar.classList.add('medium');
      } else {
        strengthBar.classList.add('strong');
      }
    });
  }

  // ========== PASSWORD MATCH CHECKER ==========
  const confirmInput = document.getElementById('confirm');
  const passwordMatchText = document.getElementById('passwordMatch');
  
  if (confirmInput && passwordInput && passwordMatchText) {
    confirmInput.addEventListener('input', function() {
      if (this.value === '') {
        passwordMatchText.textContent = '';
        passwordMatchText.className = 'form-text password-match';
        return;
      }
      
      if (this.value === passwordInput.value) {
        passwordMatchText.textContent = '✓ Lozinke se poklapaju';
        passwordMatchText.classList.add('match');
        passwordMatchText.classList.remove('no-match');
      } else {
        passwordMatchText.textContent = '✗ Lozinke se ne poklapaju';
        passwordMatchText.classList.add('no-match');
        passwordMatchText.classList.remove('match');
      }
    });
    
    // Also check when main password changes
    passwordInput.addEventListener('input', function() {
      if (confirmInput.value !== '') {
        confirmInput.dispatchEvent(new Event('input'));
      }
    });
  }

  // ========== FORM VALIDATION ==========
  const registerForm = document.getElementById('registerForm');
  
  if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
      const password = document.getElementById('password').value;
      const confirm = document.getElementById('confirm').value;
      const username = document.getElementById('username').value;
      const email = document.getElementById('email').value;
      
      // Username validation
      if (username.length < 3) {
        e.preventDefault();
        showNotification('Korisničko ime mora imati najmanje 3 karaktera', 'error');
        return;
      }
      
      // Email validation
      if (!isValidEmail(email)) {
        e.preventDefault();
        showNotification('Unesite validnu email adresu', 'error');
        return;
      }
      
      // Password validation
      if (password.length < 6) {
        e.preventDefault();
        showNotification('Lozinka mora imati najmanje 6 karaktera', 'error');
        return;
      }
      
      // Password match validation
      if (password !== confirm) {
        e.preventDefault();
        showNotification('Lozinke se ne poklapaju', 'error');
        return;
      }
      
      // Add loading state
      const submitBtn = this.querySelector('button[type="submit"]');
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Registracija...';
      submitBtn.disabled = true;
    });
  }

  // ========== LOGIN FORM VALIDATION ==========
  const loginForm = document.querySelector('form[action="../controllers/AuthController.php"]');
  
  if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      
      if (!email || !password) {
        e.preventDefault();
        showNotification('Molimo popunite sva polja', 'error');
        return;
      }
      
      if (!isValidEmail(email)) {
        e.preventDefault();
        showNotification('Unesite validnu email adresu', 'error');
        return;
      }
      
      // Add loading state
      const submitBtn = this.querySelector('button[type="submit"]');
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Prijava...';
      submitBtn.disabled = true;
    });
  }

  // ========== EMAIL VALIDATION HELPER ==========
  function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  }

  // ========== NOTIFICATION SYSTEM ==========
  function showNotification(message, type = 'error') {
    // Remove existing notification
    const existing = document.querySelector('.floating-notification');
    if (existing) existing.remove();
    
    const notification = document.createElement('div');
    notification.className = `floating-notification notification-${type}`;
    
    const icon = type === 'error' ? 'exclamation-circle' : 'check-circle';
    notification.innerHTML = `
      <i class="fas fa-${icon} me-2"></i>
      <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.classList.add('active'), 10);
    
    setTimeout(() => {
      notification.classList.remove('active');
      setTimeout(() => notification.remove(), 300);
    }, 4000);
  }

  // ========== AUTO-DISMISS ALERTS ==========
  const alerts = document.querySelectorAll('.alert-custom');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.animation = 'fadeOut 0.5s ease-out';
      setTimeout(() => alert.remove(), 500);
    }, 5000);
  });

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

  // ========== SMOOTH SCROLL TO TOP ==========
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });

  // ========== CARD ANIMATION ON LOAD ==========
  const authCard = document.querySelector('.auth-card');
  if (authCard) {
    setTimeout(() => {
      authCard.style.transform = 'scale(1)';
      authCard.style.opacity = '1';
    }, 100);
  }

  // ========== REMEMBER ME FUNCTIONALITY ==========
  const rememberCheckbox = document.getElementById('remember');
  const emailInput = document.getElementById('email');
  
  if (rememberCheckbox && emailInput) {
    // Load saved email
    const savedEmail = localStorage.getItem('rememberedEmail');
    if (savedEmail) {
      emailInput.value = savedEmail;
      rememberCheckbox.checked = true;
    }
    
    // Save email on form submit
    const loginForm = emailInput.closest('form');
    if (loginForm) {
      loginForm.addEventListener('submit', function() {
        if (rememberCheckbox.checked) {
          localStorage.setItem('rememberedEmail', emailInput.value);
        } else {
          localStorage.removeItem('rememberedEmail');
        }
      });
    }
  }

  // ========== KEYBOARD SHORTCUTS ==========
  document.addEventListener('keydown', (e) => {
    // Ctrl/Cmd + Enter to submit form
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
      const activeForm = document.activeElement.closest('form');
      if (activeForm) {
        activeForm.dispatchEvent(new Event('submit', { cancelable: true }));
      }
    }
  });

  // ========== CONSOLE EASTER EGG ==========
  console.log('%c🚗 Auto Servis Auth', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
  console.log('%cKeyboard shortcuts:\nCtrl/Cmd + Enter: Submit form', 'color: #888;');

});

// ========== FLOATING NOTIFICATION STYLES (injected) ==========
if (!document.getElementById('notificationStyles')) {
  const style = document.createElement('style');
  style.id = 'notificationStyles';
  style.textContent = `
    .floating-notification {
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
    
    .floating-notification.active {
      right: 20px;
    }
    
    .floating-notification i {
      font-size: 1.5rem;
    }
    
    .floating-notification.notification-error {
      border-left-color: #dc3545;
    }
    
    .floating-notification.notification-error i {
      color: #dc3545;
    }
    
    .floating-notification.notification-success {
      border-left-color: #28a745;
    }
    
    .floating-notification.notification-success i {
      color: #28a745;
    }
    
    .floating-notification span {
      color: var(--text-light);
      font-weight: 500;
    }
    
    @keyframes fadeOut {
      to {
        opacity: 0;
        transform: translateY(-10px);
      }
    }
    
    @media (max-width: 768px) {
      .floating-notification {
        left: 10px;
        right: 10px;
        min-width: auto;
      }
      
      .floating-notification.active {
        right: 10px;
      }
    }
  `;
  document.head.appendChild(style);
}