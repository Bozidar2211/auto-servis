/**
 * MODIFICATIONS PAGE JAVASCRIPT
 * Search, filtering, sorting i interaktivne funkcije
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

    // ========== SEARCH & FILTER ELEMENTS ==========
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const modificationCards = document.querySelectorAll('.modification-card');
    const modificationsList = document.querySelector('.modifications-list');

    // ========== SEARCH FUNCTIONALITY ==========
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            filterModifications(searchTerm, getCurrentFilter());
        });

        // Add search animation
        searchInput.addEventListener('focus', function() {
            this.parentElement.style.boxShadow = '0 0 20px rgba(240, 173, 78, 0.3)';
        });

        searchInput.addEventListener('blur', function() {
            this.parentElement.style.boxShadow = 'none';
        });
    }

    // ========== FILTER BUTTONS ==========
    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active state
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Apply filter
            const filter = btn.getAttribute('data-filter');
            filterModifications(searchInput?.value || '', filter);
        });
    });

    // ========== FILTER FUNCTION ==========
    function filterModifications(searchTerm, filterType) {
        let visibleCount = 0;
        const today = new Date();
        const thirtyDaysAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);

        modificationCards.forEach(card => {
            let shouldShow = true;

            // Search filter
            if (searchTerm) {
                const cardText = card.innerText.toLowerCase();
                shouldShow = cardText.includes(searchTerm);
            }

            // Type filter
            if (shouldShow && filterType !== 'all') {
                const modDate = new Date(card.getAttribute('data-date'));
                const cost = parseFloat(card.getAttribute('data-cost'));

                switch (filterType) {
                    case 'recent':
                        shouldShow = modDate >= thirtyDaysAgo;
                        break;
                    case 'expensive':
                        shouldShow = cost >= 10000;
                        break;
                }
            }

            // Show/hide card with animation
            if (shouldShow) {
                card.style.display = '';
                card.style.animation = 'fadeIn 0.3s ease-out';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide no results message
        updateNoResultsMessage(visibleCount);
    }

    function updateNoResultsMessage(visibleCount) {
        let noResultsMsg = document.querySelector('.no-results-message');

        if (visibleCount === 0 && modificationsList) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.className = 'no-results-message';
                noResultsMsg.style.cssText = `
                    text-align: center;
                    padding: 3rem 2rem;
                    color: var(--text-muted);
                    grid-column: 1 / -1;
                `;
                noResultsMsg.innerHTML = `
                    <i class="fas fa-search" style="font-size: 3rem; opacity: 0.5; margin-bottom: 1rem; display: block;"></i>
                    <p>Nema pronađenih modifikacija.</p>
                `;
            }
            modificationsList.appendChild(noResultsMsg);
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }

    function getCurrentFilter() {
        const activeBtn = document.querySelector('.filter-btn.active');
        return activeBtn ? activeBtn.getAttribute('data-filter') : 'all';
    }

    // ========== DELETE CONFIRMATION ==========
    window.confirmDelete = function(event) {
        event.preventDefault();
        const form = event.target;
        
        const confirmMsg = 'Da li ste sigurni da želite da obrišete ovu modifikaciju? Ova akcija se ne može poništiti!';
        
        if (confirm(confirmMsg)) {
            // Show loading state
            const deleteBtn = form.querySelector('button[type="submit"]');
            if (deleteBtn) {
                deleteBtn.disabled = true;
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Brisanje...';
            }
            
            form.submit();
        }
        
        return false;
    };

    // ========== CARD ANIMATIONS ==========
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

    modificationCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `all 0.6s ease-out ${index * 0.1}s`;
        observer.observe(card);
    });

    // ========== COPY FUNCTIONALITY (OPTIONAL) ==========
    modificationCards.forEach(card => {
        const cardBody = card.querySelector('.card-body');
        
        cardBody?.addEventListener('dblclick', (e) => {
            const description = card.querySelector('.card-description')?.innerText;
            if (description) {
                navigator.clipboard.writeText(description).then(() => {
                    showNotification('Opis kopiran u clipboard!', 'success');
                });
            }
        });
    });

    // ========== NOTIFICATION SYSTEM ==========
    window.showNotification = function(message, type = 'success') {
        const notification = document.createElement('div');
        
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

    // Add notification animations
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

        // Escape = Clear search
        if (e.key === 'Escape') {
            if (searchInput && searchInput.value) {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
            }
        }
    });

    // ========== SORT FUNCTIONALITY (BONUS) ==========
    function sortModifications(sortBy) {
        const cards = Array.from(modificationCards);
        
        cards.sort((a, b) => {
            let aValue, bValue;

            switch (sortBy) {
                case 'date-newest':
                    aValue = new Date(b.getAttribute('data-date'));
                    bValue = new Date(a.getAttribute('data-date'));
                    break;
                case 'date-oldest':
                    aValue = new Date(a.getAttribute('data-date'));
                    bValue = new Date(b.getAttribute('data-date'));
                    break;
                case 'cost-highest':
                    aValue = parseFloat(b.getAttribute('data-cost'));
                    bValue = parseFloat(a.getAttribute('data-cost'));
                    break;
                case 'cost-lowest':
                    aValue = parseFloat(a.getAttribute('data-cost'));
                    bValue = parseFloat(b.getAttribute('data-cost'));
                    break;
                default:
                    return 0;
            }

            return aValue - bValue;
        });

        // Re-arrange cards in DOM
        if (modificationsList) {
            cards.forEach((card, index) => {
                card.style.transition = `all 0.3s ease ${index * 0.05}s`;
                modificationsList.appendChild(card);
            });
        }
    }

    // ========== CONSOLE EASTER EGG ==========
    console.log('%c🔧 Modifications Page', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
    console.log('%cKeyboard shortcuts:', 'color: #888; font-weight: bold;');
    console.log('Ctrl/Cmd + F: Focus search');
    console.log('Escape: Clear search');
    console.log('Double-click: Copy description');

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

    // ========== PERFORMANCE OPTIMIZATION ==========
    document.addEventListener('visibilitychange', () => {
        const animatedBg = document.querySelector('.animated-bg');
        
        if (document.hidden) {
            if (animatedBg) animatedBg.style.animationPlayState = 'paused';
        } else {
            if (animatedBg) animatedBg.style.animationPlayState = 'running';
        }
    });

    // ========== STATS UPDATE ANIMATION ==========
    const statValues = document.querySelectorAll('.stat-value');
    
    if (statValues.length > 0) {
        const observeStats = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'countUp 0.6s ease-out';
                }
            });
        }, { threshold: 0.5 });

        statValues.forEach(stat => {
            observeStats.observe(stat);
        });
    }

    // Add count-up animation
    if (!document.querySelector('#countUpStyles')) {
        const style = document.createElement('style');
        style.id = 'countUpStyles';
        style.textContent = `
            @keyframes countUp {
                from {
                    transform: translateY(20px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    }

});