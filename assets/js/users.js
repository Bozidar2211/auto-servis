/**
 * USERS PAGE JAVASCRIPT
 * Premium animations, search/filter, and interactive features
 */

document.addEventListener('DOMContentLoaded', () => {

  // ========== SEARCH AND FILTER FUNCTIONALITY ==========
  const searchInput = document.getElementById('searchInput');
  const roleFilter = document.getElementById('roleFilter');
  const userCards = document.querySelectorAll('.user-card');

  function filterUsers() {
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const selectedRole = roleFilter ? roleFilter.value : 'all';
    let visibleCount = 0;

    userCards.forEach((card, index) => {
      const username = card.dataset.username ? card.dataset.username.toLowerCase() : '';
      const email = card.dataset.email ? card.dataset.email.toLowerCase() : '';
      const role = card.dataset.role || '';

      const matchesSearch = username.includes(searchTerm) || email.includes(searchTerm);
      const matchesRole = selectedRole === 'all' || role === selectedRole;

      if (matchesSearch && matchesRole) {
        card.style.display = 'block';
        card.style.animationDelay = `${visibleCount * 0.05}s`;
        card.classList.add('fade-in');
        visibleCount++;
      } else {
        card.style.display = 'none';
        card.classList.remove('fade-in');
      }
    });

    // Show empty state if no results
    updateEmptyState(visibleCount);
  }

  function updateEmptyState(count) {
    let emptyState = document.querySelector('.search-empty-state');
    
    if (count === 0 && userCards.length > 0) {
      if (!emptyState) {
        emptyState = document.createElement('div');
        emptyState.className = 'search-empty-state fade-in';
        emptyState.innerHTML = `
          <div class="empty-icon">
            <i class="fas fa-search"></i>
          </div>
          <h4>Nema rezultata</h4>
          <p>Pokušajte sa drugačijim kriterijumima pretrage</p>
        `;
        document.querySelector('.users-grid').appendChild(emptyState);
      }
      emptyState.style.display = 'block';
    } else if (emptyState) {
      emptyState.style.display = 'none';
    }
  }

  if (searchInput) {
    searchInput.addEventListener('input', filterUsers);
    
    // Clear search button
    searchInput.addEventListener('input', function() {
      if (this.value) {
        if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('clear-search')) {
          const clearBtn = document.createElement('button');
          clearBtn.className = 'clear-search';
          clearBtn.innerHTML = '<i class="fas fa-times"></i>';
          clearBtn.onclick = () => {
            searchInput.value = '';
            filterUsers();
            clearBtn.remove();
          };
          this.parentElement.appendChild(clearBtn);
        }
      } else {
        const clearBtn = this.nextElementSibling;
        if (clearBtn && clearBtn.classList.contains('clear-search')) {
          clearBtn.remove();
        }
      }
    });
  }

  if (roleFilter) {
    roleFilter.addEventListener('change', filterUsers);
  }

  // ========== USER CARD HOVER EFFECTS ==========
  userCards.forEach(card => {
    // 3D tilt effect on mouse move
    card.addEventListener('mousemove', function(e) {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      const centerX = rect.width / 2;
      const centerY = rect.height / 2;
      
      const rotateX = (y - centerY) / 30;
      const rotateY = (centerX - x) / 30;
      
      card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px)`;
    });
    
    card.addEventListener('mouseleave', function() {
      card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
    });

    // Avatar animation on hover
    card.addEventListener('mouseenter', function() {
      const avatar = this.querySelector('.user-avatar-large');
      if (avatar) {
        avatar.style.transform = 'scale(1.15) rotate(10deg)';
      }
    });

    card.addEventListener('mouseleave', function() {
      const avatar = this.querySelector('.user-avatar-large');
      if (avatar) {
        avatar.style.transform = 'scale(1) rotate(0deg)';
      }
    });
  });

  // ========== DELETE CONFIRMATION MODAL ==========
  let formToSubmit = null;

  document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      formToSubmit = this;
      
      const username = this.closest('.user-card').querySelector('.user-card-name').textContent;
      showConfirmModal(username);
    });
  });

  function showConfirmModal(username) {
    const overlay = document.getElementById('confirmOverlay');
    const modal = overlay.querySelector('.confirm-modal');
    const message = modal.querySelector('p');
    
    if (message) {
      message.textContent = `Da li ste sigurni da želite da obrišete korisnika "${username}"? Ova akcija je nepovratna.`;
    }
    
    overlay.classList.add('active');
    setTimeout(() => modal.classList.add('active'), 10);
  }

  window.closeConfirmModal = function() {
    const overlay = document.getElementById('confirmOverlay');
    const modal = overlay.querySelector('.confirm-modal');
    
    modal.classList.remove('active');
    setTimeout(() => {
      overlay.classList.remove('active');
      formToSubmit = null;
    }, 300);
  };

  const confirmDeleteBtn = document.getElementById('confirmDelete');
  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener('click', function() {
      if (formToSubmit) {
        // Add loading state
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Brisanje...';
        this.disabled = true;
        
        // Show notification
        showNotification('Brisanje u toku...', 'info');
        
        // Submit after animation
        setTimeout(() => {
          formToSubmit.submit();
        }, 500);
      }
    });
  }

  // Close modal on overlay click
  const confirmOverlay = document.getElementById('confirmOverlay');
  if (confirmOverlay) {
    confirmOverlay.addEventListener('click', function(e) {
      if (e.target === this) {
        closeConfirmModal();
      }
    });
  }

  // ========== RIPPLE EFFECT ON BUTTONS ==========
  document.querySelectorAll('.btn-user-action, .btn-action, .btn-refresh, .btn-filter, .btn-export').forEach(button => {
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

  // ========== STAT CARDS COUNTER ANIMATION ==========
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

  document.querySelectorAll('.stat-number').forEach(stat => {
    const target = parseInt(stat.textContent);
    if (!isNaN(target)) {
      stat.textContent = '0';
      animateCounter(stat, target);
    }
  });

  // ========== NOTIFICATION SYSTEM ==========
  window.showNotification = function(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icon = type === 'success' ? 'check-circle' : 
                 type === 'error' ? 'exclamation-circle' : 
                 type === 'info' ? 'info-circle' :
                 'bell';
    
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

  // ========== ALERT AUTO-CLOSE ==========
  document.querySelectorAll('.alert-custom').forEach(alert => {
    const closeBtn = alert.querySelector('.alert-close');
    
    if (closeBtn) {
      closeBtn.addEventListener('click', function() {
        alert.style.animation = 'slideOut 0.3s ease-out forwards';
        setTimeout(() => alert.remove(), 300);
      });
    }

    // Auto-close after 5 seconds
    setTimeout(() => {
      if (alert.parentElement) {
        alert.style.animation = 'slideOut 0.3s ease-out forwards';
        setTimeout(() => alert.remove(), 300);
      }
    }, 5000);
  });

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

  document.querySelectorAll('.user-card, .stat-card').forEach(el => {
    observer.observe(el);
  });

  // ========== LOADING STATE FOR EDIT BUTTONS ==========
  document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function(e) {
      const icon = this.querySelector('i');
      if (icon) {
        const originalClass = icon.className;
        icon.className = 'fas fa-spinner fa-spin';
        
        // Restore if navigation doesn't happen
        setTimeout(() => {
          icon.className = originalClass;
        }, 2000);
      }
    });
  });

  // ========== KEYBOARD SHORTCUTS ==========
  document.addEventListener('keydown', (e) => {
    // Ctrl/Cmd + F = Focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
      e.preventDefault();
      if (searchInput) {
        searchInput.focus();
        searchInput.select();
      }
    }
    
    // Ctrl/Cmd + B = Back to dashboard
    if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
      e.preventDefault();
      window.location.href = '/auto-servis/admin.php?controller=user&action=dashboard';
    }
    
    // Escape = Close modal or clear search
    if (e.key === 'Escape') {
      const overlay = document.getElementById('confirmOverlay');
      if (overlay && overlay.classList.contains('active')) {
        closeConfirmModal();
      } else if (searchInput && searchInput.value) {
        searchInput.value = '';
        filterUsers();
      }
    }
    
    // Alt + 1-4 = Filter by role
    if (e.altKey && ['1', '2', '3', '4'].includes(e.key)) {
      e.preventDefault();
      if (roleFilter) {
        const options = ['all', 'admin', 'mechanic', 'user'];
        roleFilter.value = options[parseInt(e.key) - 1];
        filterUsers();
        showNotification(`Filter: ${roleFilter.options[roleFilter.selectedIndex].text}`, 'info');
      }
    }
  });

  // ========== EXPORT FUNCTIONALITY (Placeholder) ==========
  window.exportUsers = function() {
    showNotification('Export funkcionalnost će biti dostupna uskoro!', 'info');
    
    // Simulate export process
    const btn = document.querySelector('.btn-export');
    if (btn) {
      const originalHTML = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Export...';
      btn.disabled = true;
      
      setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        showNotification('Export završen!', 'success');
      }, 2000);
    }
  };

  // ========== FILTER TOGGLE (Placeholder) ==========
  window.toggleFilters = function() {
    showNotification('Napredni filteri će biti dostupni uskoro!', 'info');
  };

  // ========== ROLE BADGE CLICK INFO ==========
  document.querySelectorAll('.user-role-badge').forEach(badge => {
    badge.addEventListener('click', function(e) {
      e.stopPropagation();
      const role = this.textContent.trim();
      showNotification(`Uloga: ${role}`, 'info');
    });
  });

  // ========== STATS BAR ANIMATION ON SCROLL ==========
  const statsBar = document.querySelector('.users-stats');
  if (statsBar) {
    const statsObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.querySelectorAll('.stat-card').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.style.animation = 'fadeIn 0.6s ease-out forwards';
          });
          statsObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    
    statsObserver.observe(statsBar);
  }

  // ========== SMOOTH SCROLL FOR BREADCRUMB ==========
  document.querySelectorAll('.breadcrumb a').forEach(link => {
    link.addEventListener('click', function(e) {
      const icon = this.querySelector('i');
      if (icon) {
        icon.style.animation = 'iconPulse 0.5s ease-out';
      }
    });
  });

  // ========== HEADER SCROLL EFFECT ==========
  let lastScroll = 0;
  const header = document.querySelector('.admin-header');
  
  if (header) {
    window.addEventListener('scroll', () => {
      const currentScroll = window.pageYOffset;
      
      if (currentScroll > 100) {
        header.style.boxShadow = '0 8px 30px rgba(0, 0, 0, 0.8)';
      } else {
        header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.5)';
      }
      
      lastScroll = currentScroll;
    });
  }

  // ========== CONSOLE EASTER EGG ==========
  console.log('%c👥 Users Management', 'font-size: 24px; font-weight: bold; color: #f0ad4e; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);');
  console.log('%cKeyboard Shortcuts:', 'font-size: 14px; font-weight: bold; color: #0dcaf0;');
  console.log('%cCtrl+F → Search\nCtrl+B → Back to Dashboard\nAlt+1 → All Users\nAlt+2 → Admins\nAlt+3 → Mechanics\nAlt+4 → Users\nEscape → Close/Clear', 'color: #888;');
  console.log(`%c\nTotal Users: ${userCards.length}`, 'color: #28a745; font-weight: bold;');

  // ========== WELCOME ANIMATION ==========
  const pageHeader = document.querySelector('.page-header');
  if (pageHeader) {
    pageHeader.style.animation = 'slideInDown 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
  }

  // ========== CARD STAGGER ANIMATION ==========
  userCards.forEach((card, index) => {
    card.style.animationDelay = `${0.3 + (index * 0.05)}s`;
  });

  // ========== REFRESH BUTTON ANIMATION ==========
  const refreshBtn = document.querySelector('.btn-refresh');
  if (refreshBtn) {
    refreshBtn.addEventListener('click', function() {
      this.querySelector('i').style.animation = 'spin 1s linear';
      setTimeout(() => {
        this.querySelector('i').style.animation = '';
      }, 1000);
    });
  }

  // ========== PERFORMANCE MONITORING ==========
  if (window.performance && window.performance.timing) {
    const loadTime = window.performance.timing.domContentLoadedEventEnd - window.performance.timing.navigationStart;
    console.log(`%c⚡ Page loaded in ${loadTime}ms`, 'color: #ffc107; font-weight: bold;');
  }

});

// ========== INJECT CUSTOM ANIMATION STYLES ==========
if (!document.getElementById('usersCustomStyles')) {
  const style = document.createElement('style');
  style.id = 'usersCustomStyles';
  style.textContent = `
    /* Ripple Animation */
    @keyframes ripple {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }

    /* Clear Search Button */
    .clear-search {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(220, 53, 69, 0.2);
      border: 1px solid rgba(220, 53, 69, 0.3);
      border-radius: 50%;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #dc3545;
      cursor: pointer;
      transition: all 0.3s ease;
      z-index: 10;
    }

    .clear-search:hover {
      background: #dc3545;
      color: white;
      transform: translateY(-50%) rotate(90deg);
    }

    /* Search Empty State */
    .search-empty-state {
      grid-column: 1 / -1;
      text-align: center;
      padding: 4rem 2rem;
      background: rgba(255, 255, 255, 0.03);
      border: 2px dashed rgba(240, 173, 78, 0.3);
      border-radius: 16px;
    }

    .search-empty-state .empty-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 1.5rem;
      background: rgba(240, 173, 78, 0.1);
      border: 2px solid rgba(240, 173, 78, 0.3);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      color: rgba(240, 173, 78, 0.5);
    }

    .search-empty-state h4 {
      color: var(--text-light);
      margin-bottom: 0.5rem;
    }

    .search-empty-state p {
      color: var(--text-muted);
    }

    /* Notification Styles */
    .notification {
      position: fixed;
      top: 20px;
      right: -400px;
      background: rgba(30, 30, 30, 0.98);
      border-left: 4px solid var(--primary-color);
      border-radius: 12px;
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
    
    .notification-error {
      border-left-color: #dc3545;
    }
    
    .notification-error i {
      color: #dc3545;
    }

    .notification-info {
      border-left-color: #0dcaf0;
    }
    
    .notification-info i {
      color: #0dcaf0;
    }
    
    .notification span {
      color: var(--text-light);
      font-weight: 500;
    }

    /* Spin Animation */
    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* Icon Pulse */
    @keyframes iconPulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.2); }
    }

    /* Responsive */
    @media (max-width: 768px) {
      .notification {
        left: 10px;
        right: 10px;
        min-width: auto;
      }
      
      .notification.active {
        right: 10px;
      }

      .clear-search {
        right: 0.75rem;
      }
    }
  `;
  document.head.appendChild(style);
}