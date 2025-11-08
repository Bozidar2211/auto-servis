/**
 * MECHANIC DASHBOARD JAVASCRIPT
 * Animacije, interakcije i real-time updates
 */

document.addEventListener('DOMContentLoaded', () => {

  // ========== COUNTER ANIMATION ==========
  const animateCounter = (element, target, duration = 1500) => {
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

  // Animate all stat numbers
  document.querySelectorAll('.stat-number[data-count]').forEach(counter => {
    const target = parseInt(counter.getAttribute('data-count'));
    if (!isNaN(target)) {
      counter.textContent = '0';
      setTimeout(() => {
        animateCounter(counter, target);
      }, 300);
    }
  });

  // ========== STAT CARDS HOVER EFFECT ==========
  document.querySelectorAll('.stat-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
      const icon = this.querySelector('.stat-icon i');
      if (icon) {
        icon.style.transform = 'scale(1.2) rotate(10deg)';
        icon.style.transition = 'transform 0.3s ease';
      }
    });
    
    card.addEventListener('mouseleave', function() {
      const icon = this.querySelector('.stat-icon i');
      if (icon) {
        icon.style.transform = 'scale(1) rotate(0deg)';
      }
    });
  });

  // ========== REQUEST ROWS REVEAL ANIMATION ==========
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

  document.querySelectorAll('.request-row').forEach(row => {
    observer.observe(row);
  });

  // ========== FILTER BUTTONS ANIMATION ==========
  document.querySelectorAll('.btn-filter').forEach(btn => {
    btn.addEventListener('click', function(e) {
      // Add loading animation
      const icon = this.querySelector('i');
      if (icon) {
        const originalClass = icon.className;
        icon.className = 'fas fa-spinner fa-spin';
        
        // Restore after navigation
        setTimeout(() => {
          icon.className = originalClass;
        }, 1000);
      }
    });
  });

  // ========== REFRESH BUTTON ANIMATION ==========
  const refreshBtn = document.querySelector('.btn-refresh');
  if (refreshBtn) {
    refreshBtn.addEventListener('click', function() {
      this.style.pointerEvents = 'none';
      this.querySelector('i').style.animation = 'spin 1s linear infinite';
      
      setTimeout(() => {
        this.style.pointerEvents = 'auto';
        this.querySelector('i').style.animation = '';
      }, 1000);
    });
  }

  // ========== ACTION BUTTONS CONFIRMATION ==========
  document.querySelectorAll('.btn-action-complete').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Da li ste sigurni da želite da označite ovaj zahtev kao završen?')) {
        e.preventDefault();
      }
    });
  });

  // ========== STATUS BADGE ANIMATION ==========
  document.querySelectorAll('.status-badge').forEach(badge => {
    badge.addEventListener('mouseenter', function() {
      this.style.transform = 'scale(1.1)';
    });
    
    badge.addEventListener('mouseleave', function() {
      this.style.transform = 'scale(1)';
    });
  });

  // ========== TABLE ROW CLICK (expand details - optional) ==========
  let expandedRow = null;
  
  document.querySelectorAll('.request-row').forEach(row => {
    row.addEventListener('click', function(e) {
      // Don't trigger on action button clicks
      if (e.target.closest('.action-buttons')) return;
      
      // Toggle highlight
      if (expandedRow === this) {
        this.style.background = 'rgba(255, 255, 255, 0.03)';
        expandedRow = null;
      } else {
        if (expandedRow) {
          expandedRow.style.background = 'rgba(255, 255, 255, 0.03)';
        }
        this.style.background = 'rgba(240, 173, 78, 0.15)';
        expandedRow = this;
      }
    });
  });

  // ========== AUTO REFRESH (Optional - every 30 seconds) ==========
  const autoRefresh = false; // Set to true to enable
  
  if (autoRefresh) {
    let refreshInterval = setInterval(() => {
      // Check if user is still on page
      if (!document.hidden) {
        console.log('Auto-refreshing dashboard...');
        location.reload();
      }
    }, 30000); // 30 seconds

    // Clear interval when page is hidden
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) {
        clearInterval(refreshInterval);
      }
    });
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

  // ========== KEYBOARD SHORTCUTS ==========
  document.addEventListener('keydown', (e) => {
    // R = Refresh
    if (e.key === 'r' || e.key === 'R') {
      if (!e.ctrlKey && !e.metaKey) {
        e.preventDefault();
        location.reload();
      }
    }
    
    // A = Show active requests
    if (e.key === 'a' || e.key === 'A') {
      if (!e.ctrlKey && !e.metaKey) {
        e.preventDefault();
        window.location.href = '/auto-servis/mechanic.php?controller=mechanic&action=dashboard&filter=active';
      }
    }
    
    // S = Show all requests
    if (e.key === 's' || e.key === 'S') {
      if (!e.ctrlKey && !e.metaKey) {
        e.preventDefault();
        window.location.href = '/auto-servis/mechanic.php?controller=mechanic&action=dashboard&filter=all';
      }
    }
  });

  // ========== SCROLL TO TOP ON FILTER CHANGE ==========
  document.querySelectorAll('.btn-filter').forEach(btn => {
    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });

  // ========== LOADING OVERLAY (for actions) ==========
  function showLoadingOverlay() {
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay';
    overlay.innerHTML = `
      <div class="loading-spinner">
        <i class="fas fa-cog fa-spin"></i>
        <p>Učitavanje...</p>
      </div>
    `;
    document.body.appendChild(overlay);
    
    setTimeout(() => overlay.classList.add('active'), 10);
    
    return overlay;
  }

  function hideLoadingOverlay(overlay) {
    overlay.classList.remove('active');
    setTimeout(() => overlay.remove(), 300);
  }

  // Add to action buttons
  document.querySelectorAll('.btn-action').forEach(btn => {
    btn.addEventListener('click', function(e) {
      // Show loading for navigation
      const overlay = showLoadingOverlay();
      
      // Hide after 2 seconds (in case navigation doesn't happen)
      setTimeout(() => {
        if (document.body.contains(overlay)) {
          hideLoadingOverlay(overlay);
        }
      }, 2000);
    });
  });

  // ========== HIGHLIGHT NEW REQUESTS ==========
  // Check if there are new requests since last visit
  const lastVisit = localStorage.getItem('mechanic_last_visit');
  const currentTime = Date.now();
  
  if (lastVisit) {
    const timeSince = currentTime - parseInt(lastVisit);
    // If visited within last 5 minutes, highlight might be new
    if (timeSince < 300000) {
      document.querySelectorAll('.status-pending').forEach((badge, index) => {
        if (index < 2) { // Highlight first 2 pending
          badge.style.animation = 'pulse 2s ease-in-out infinite';
        }
      });
    }
  }
  
  // Update last visit time
  localStorage.setItem('mechanic_last_visit', currentTime.toString());

  // ========== SEARCH/FILTER IN TABLE (optional) ==========
  window.filterTable = function(query) {
    const rows = document.querySelectorAll('.request-row');
    const lowerQuery = query.toLowerCase();
    
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      if (text.includes(lowerQuery)) {
        row.style.display = '';
        row.style.animation = 'fadeIn 0.5s ease-out';
      } else {
        row.style.display = 'none';
      }
    });
  };

  // ========== CONSOLE EASTER EGG ==========
  console.log('%c🔧 Mehaničarski Panel', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
  console.log('%cKeyboard shortcuts:\nR: Refresh\nA: Active requests\nS: Show all', 'color: #888;');
  console.log('%cAuto-refresh: ' + (autoRefresh ? 'Enabled (30s)' : 'Disabled'), 'color: #888;');

});

// ========== INJECT NOTIFICATION STYLES ==========
if (!document.getElementById('mechanicNotificationStyles')) {
  const style = document.createElement('style');
  style.id = 'mechanicNotificationStyles';
  style.textContent = `
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
    
    .notification-error {
      border-left-color: #dc3545;
    }
    
    .notification-error i {
      color: #dc3545;
    }
    
    .notification span {
      color: var(--text-light);
      font-weight: 500;
    }
    
    .loading-overlay {
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
    
    .loading-overlay.active {
      opacity: 1;
    }
    
    .loading-spinner {
      text-align: center;
    }
    
    .loading-spinner i {
      font-size: 3rem;
      color: var(--primary-color);
      margin-bottom: 1rem;
    }
    
    .loading-spinner p {
      color: var(--text-light);
      font-size: 1.1rem;
      font-weight: 600;
    }
    
    @keyframes pulse {
      0%, 100% {
        box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
      }
      50% {
        box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
      }
    }
    
    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
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
    }
  `;
  document.head.appendChild(style);
}