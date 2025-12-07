/**
 * ADMIN DASHBOARD JAVASCRIPT
 * Premium animations, real-time updates, and interactive features
 */

document.addEventListener('DOMContentLoaded', () => {

  // ========== REAL-TIME CLOCK ==========
  function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('sr-RS', { 
      hour: '2-digit', 
      minute: '2-digit',
      second: '2-digit'
    });
    const dateString = now.toLocaleDateString('sr-RS', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
    
    const clockElement = document.getElementById('currentTime');
    if (clockElement) {
      clockElement.innerHTML = `
        <div style="display: flex; flex-direction: column; align-items: flex-end;">
          <span style="font-size: 1.2rem;">${timeString}</span>
          <span style="font-size: 0.75rem; opacity: 0.7;">${dateString}</span>
        </div>
      `;
    }
  }

  updateClock();
  setInterval(updateClock, 1000);


  // ========== CARD HOVER 3D EFFECT ==========
  document.querySelectorAll('.admin-card').forEach(card => {
    card.addEventListener('mousemove', function(e) {
      const rect = this.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      const centerX = rect.width / 2;
      const centerY = rect.height / 2;
      
      const rotateX = (y - centerY) / 20;
      const rotateY = (centerX - x) / 20;
      
      this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px)`;
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
    });
  });

  // ========== STAT CARDS ANIMATION ==========
  document.querySelectorAll('.stat-mini').forEach((card, index) => {
    card.style.animationDelay = `${index * 0.1}s`;
    card.style.animation = 'fadeIn 0.6s ease-out forwards';
  });

  // ========== QUICK ACCESS BUTTONS RIPPLE ==========
  document.querySelectorAll('.quick-btn, .btn-admin').forEach(button => {
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

  // ========== EXPORT MODAL ==========
  window.showExportModal = function() {
    showCustomModal(
      'Export Podataka',
      `
        <div style="padding: 1rem;">
          <p style="color: var(--text-muted); margin-bottom: 1rem;">
            Izaberite format za export podataka:
          </p>
          <div style="display: flex; gap: 1rem; flex-direction: column;">
            <button onclick="exportData('pdf')" class="modal-btn modal-btn-primary">
              <i class="fas fa-file-pdf me-2"></i>Export u PDF
            </button>
            <button onclick="exportData('excel')" class="modal-btn modal-btn-success">
              <i class="fas fa-file-excel me-2"></i>Export u Excel
            </button>
            <button onclick="exportData('csv')" class="modal-btn modal-btn-info">
              <i class="fas fa-file-csv me-2"></i>Export u CSV
            </button>
          </div>
        </div>
      `
    );
  };

  window.exportData = function(format) {
    closeCustomModal();
    showNotification(`Export u ${format.toUpperCase()} format je pokrenut!`, 'success');
    
    // Simuliraj download
    setTimeout(() => {
      showNotification('Download završen!', 'success');
    }, 2000);
  };

  // ========== BACKUP MODAL ==========
  window.showBackupModal = function() {
    showCustomModal(
      'Backup Baze Podataka',
      `
        <div style="padding: 1rem;">
          <p style="color: var(--text-muted); margin-bottom: 1rem;">
            <i class="fas fa-exclamation-triangle" style="color: #ffc107;"></i>
            Kreiranje backup-a može potrajati nekoliko minuta.
          </p>
          <div style="display: flex; gap: 1rem; flex-direction: column;">
            <button onclick="createBackup('full')" class="modal-btn modal-btn-primary">
              <i class="fas fa-database me-2"></i>Full Backup
            </button>
            <button onclick="createBackup('partial')" class="modal-btn modal-btn-warning">
              <i class="fas fa-layer-group me-2"></i>Partial Backup
            </button>
          </div>
        </div>
      `
    );
  };

  window.createBackup = function(type) {
    closeCustomModal();
    showLoadingOverlay('Kreiranje backup-a...');
    
    setTimeout(() => {
      hideLoadingOverlay();
      showNotification(`${type === 'full' ? 'Full' : 'Partial'} backup uspešno kreiran!`, 'success');
    }, 3000);
  };

  // ========== CUSTOM MODAL SYSTEM ==========
  function showCustomModal(title, content) {
    const modal = document.createElement('div');
    modal.className = 'custom-modal-overlay';
    modal.id = 'customModal';
    
    modal.innerHTML = `
      <div class="custom-modal">
        <div class="custom-modal-header">
          <h4>${title}</h4>
          <button class="modal-close" onclick="closeCustomModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="custom-modal-body">
          ${content}
        </div>
      </div>
    `;
    
    document.body.appendChild(modal);
    setTimeout(() => modal.classList.add('active'), 10);
    
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        closeCustomModal();
      }
    });
  }

  window.closeCustomModal = function() {
    const modal = document.getElementById('customModal');
    if (modal) {
      modal.classList.remove('active');
      setTimeout(() => modal.remove(), 300);
    }
  };

  // ========== LOADING OVERLAY ==========
  function showLoadingOverlay(message = 'Učitavanje...') {
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay';
    overlay.id = 'loadingOverlay';
    overlay.innerHTML = `
      <div class="loading-spinner">
        <i class="fas fa-cog fa-spin"></i>
        <p>${message}</p>
      </div>
    `;
    document.body.appendChild(overlay);
    setTimeout(() => overlay.classList.add('active'), 10);
  }

  function hideLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
      overlay.classList.remove('active');
      setTimeout(() => overlay.remove(), 300);
    }
  }

  // ========== NOTIFICATION SYSTEM ==========
  window.showNotification = function(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icon = type === 'success' ? 'check-circle' : 
                 type === 'error' ? 'exclamation-circle' : 
                 'info-circle';
    
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
    // Ctrl/Cmd + U = Users
    if ((e.ctrlKey || e.metaKey) && e.key === 'u') {
      e.preventDefault();
      window.location.href = '/auto-servis/admin.php?controller=user&action=index';
    }
    
    // Ctrl/Cmd + S = Stats
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
      e.preventDefault();
      window.location.href = '/auto-servis/admin.php?controller=report&action=overview';
    }
    
    // Ctrl/Cmd + E = Export
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
      e.preventDefault();
      showExportModal();
    }
    
    // Ctrl/Cmd + B = Backup
    if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
      e.preventDefault();
      showBackupModal();
    }
    
    // Escape = Close modal
    if (e.key === 'Escape') {
      closeCustomModal();
    }
  });

  // ========== SCROLL ANIMATIONS ==========
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

  document.querySelectorAll('.admin-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    card.style.transition = 'all 0.6s ease-out';
    observer.observe(card);
  });

  // ========== CONSOLE EASTER EGG ==========
  console.log('%c👑 Admin Control Panel', 'font-size: 24px; font-weight: bold; color: #f0ad4e; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);');
  console.log('%cKeyboard Shortcuts:', 'font-size: 14px; font-weight: bold; color: #0dcaf0;');
  console.log('%cCtrl+U → Users\nCtrl+S → Stats\nCtrl+E → Export\nCtrl+B → Backup\nEscape → Close Modal', 'color: #888;');
  console.log('%c\nSystem Status: Online ✓', 'color: #28a745; font-weight: bold;');

});

// ========== INJECT CUSTOM STYLES ==========
if (!document.getElementById('adminCustomStyles')) {
  const style = document.createElement('style');
  style.id = 'adminCustomStyles';
  style.textContent = `
    /* Ripple Animation */
    @keyframes ripple {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }

    /* Notification Styles */
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

    /* Custom Modal */
    .custom-modal-overlay {
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
    
    .custom-modal-overlay.active {
      opacity: 1;
    }
    
    .custom-modal {
      background: rgba(20, 20, 20, 0.98);
      border: 1px solid rgba(240, 173, 78, 0.3);
      border-radius: 16px;
      min-width: 400px;
      max-width: 90%;
      transform: scale(0.7);
      opacity: 0;
      transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    .custom-modal-overlay.active .custom-modal {
      transform: scale(1);
      opacity: 1;
    }
    
    .custom-modal-header {
      padding: 1.5rem;
      border-bottom: 1px solid rgba(240, 173, 78, 0.2);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .custom-modal-header h4 {
      color: var(--text-light);
      margin: 0;
      font-weight: 700;
    }
    
    .modal-close {
      background: transparent;
      border: none;
      color: var(--text-muted);
      font-size: 1.5rem;
      cursor: pointer;
      width: 35px;
      height: 35px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }
    
    .modal-close:hover {
      background: rgba(220, 53, 69, 0.2);
      color: #dc3545;
    }
    
    .custom-modal-body {
      padding: 1rem;
    }
    
    .modal-btn {
      width: 100%;
      padding: 0.875rem 1.5rem;
      border-radius: 10px;
      border: none;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
    }
    
    .modal-btn-primary {
      background: rgba(13, 110, 253, 0.2);
      color: #0d6efd;
      border: 2px solid #0d6efd;
    }
    
    .modal-btn-primary:hover {
      background: #0d6efd;
      color: white;
    }
    
    .modal-btn-success {
      background: rgba(40, 167, 69, 0.2);
      color: #28a745;
      border: 2px solid #28a745;
    }
    
    .modal-btn-success:hover {
      background: #28a745;
      color: white;
    }
    
    .modal-btn-info {
      background: rgba(13, 202, 240, 0.2);
      color: #0dcaf0;
      border: 2px solid #0dcaf0;
    }
    
    .modal-btn-info:hover {
      background: #0dcaf0;
      color: #111;
    }
    
    .modal-btn-warning {
      background: rgba(255, 193, 7, 0.2);
      color: #ffc107;
      border: 2px solid #ffc107;
    }
    
    .modal-btn-warning:hover {
      background: #ffc107;
      color: #111;
    }

    /* Loading Overlay */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.9);
      backdrop-filter: blur(5px);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10001;
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
      font-size: 4rem;
      color: var(--primary-color);
      margin-bottom: 1rem;
    }
    
    .loading-spinner p {
      color: var(--text-light);
      font-size: 1.2rem;
      font-weight: 600;
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
      
      .custom-modal {
        min-width: 95%;
      }
    }
  `;
  document.head.appendChild(style);
}