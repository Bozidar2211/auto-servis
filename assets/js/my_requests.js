/**
 * MY REQUESTS PAGE JAVASCRIPT
 * Filtering, search, modals i interaktivne funkcije
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

    // ========== FILTER & SEARCH ELEMENTS ==========
    const filterButtons = document.querySelectorAll('.filter-btn');
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearch');
    const requestsGrid = document.getElementById('requestsGrid');
    const requestCards = document.querySelectorAll('.request-card');

    let currentFilter = 'all';
    let currentSearch = '';

    // ========== FILTER FUNCTIONALITY ==========
    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            currentFilter = btn.dataset.filter;
            applyFiltersAndSearch();
        });
    });

    // ========== SEARCH FUNCTIONALITY ==========
    searchInput?.addEventListener('input', (e) => {
        currentSearch = e.target.value.toLowerCase();
        
        // Show/hide clear button
        if (currentSearch) {
            clearSearchBtn.classList.add('active');
        } else {
            clearSearchBtn.classList.remove('active');
        }
        
        applyFiltersAndSearch();
    });

    clearSearchBtn?.addEventListener('click', () => {
        searchInput.value = '';
        currentSearch = '';
        clearSearchBtn.classList.remove('active');
        applyFiltersAndSearch();
        searchInput.focus();
    });

    // ========== APPLY FILTERS AND SEARCH ==========
    function applyFiltersAndSearch() {
        let visibleCount = 0;
        let filteredCounts = {
            pending: 0,
            approved: 0,
            in_progress: 0,
            completed: 0,
            rejected: 0
        };

        requestCards.forEach(card => {
            const status = card.dataset.status;
            const title = card.querySelector('.request-title').textContent.toLowerCase();
            const id = card.querySelector('.request-id').textContent.toLowerCase();
            const description = card.querySelector('.description-preview').textContent.toLowerCase();
            
            // Check filter
            const statusMatch = currentFilter === 'all' || status === currentFilter;
            
            // Check search
            const searchMatch = currentSearch === '' || 
                               title.includes(currentSearch) ||
                               id.includes(currentSearch) ||
                               description.includes(currentSearch);
            
            const shouldShow = statusMatch && searchMatch;
            
            if (shouldShow) {
                card.style.display = '';
                card.style.animation = 'fadeInUp 0.4s ease-out';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }

            // Count by status for stats
            if (searchMatch) {
                if (status in filteredCounts) {
                    filteredCounts[status]++;
                }
            }
        });

        // Update filter button counts
        updateFilterCounts(filteredCounts);

        // Show empty message if no results
        if (visibleCount === 0) {
            showNoResults();
        } else {
            hideNoResults();
        }
    }

    // ========== UPDATE FILTER COUNTS ==========
    function updateFilterCounts(counts) {
        filterButtons.forEach(btn => {
            const filter = btn.dataset.filter;
            const countValue = filter === 'all' 
                ? Object.values(counts).reduce((a, b) => a + b, 0)
                : (counts[filter] || 0);
            
            // Extract just the button content without count
            const buttonText = btn.innerHTML.split('(')[0].trim();
            const icon = btn.querySelector('i');
            
            btn.innerHTML = '';
            if (icon) {
                btn.appendChild(icon.cloneNode());
                btn.innerHTML += ' ';
            }
            
            // Get text content
            const label = buttonText.replace(/<[^>]*>/g, '').trim();
            btn.innerHTML += `${label} (${countValue})`;
        });

        // Update stats
        updateStats(counts);
    }

    // ========== UPDATE STATS ==========
    function updateStats(counts) {
        const statsMap = {
            pendingCount: counts.pending || 0,
            approvedCount: counts.approved || 0,
            progressCount: counts.in_progress || 0,
            completedCount: counts.completed || 0
        };

        Object.entries(statsMap).forEach(([id, count]) => {
            const element = document.getElementById(id);
            if (element) {
                animateCountChange(element, parseInt(element.textContent), count);
            }
        });
    }

    function animateCountChange(element, oldValue, newValue) {
        if (oldValue === newValue) return;
        
        let current = oldValue;
        const step = Math.ceil((newValue - oldValue) / 10);
        
        const interval = setInterval(() => {
            current += step;
            if ((step > 0 && current >= newValue) || (step < 0 && current <= newValue)) {
                current = newValue;
                clearInterval(interval);
            }
            element.textContent = current;
        }, 30);
    }

    // ========== SHOW/HIDE NO RESULTS ==========
    function showNoResults() {
        let noResultsMsg = document.getElementById('noResults');
        if (!noResultsMsg) {
            noResultsMsg = document.createElement('div');
            noResultsMsg.id = 'noResults';
            noResultsMsg.className = 'empty-state';
            noResultsMsg.innerHTML = `
                <div class="empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Nema Rezultata</h3>
                <p>Nema zahteva koji odgovaraju vašoj pretrazi ili filteru.</p>
            `;
            requestsGrid?.parentElement?.insertBefore(noResultsMsg, requestsGrid);
        }
        noResultsMsg.style.display = 'block';
        if (requestsGrid) requestsGrid.style.display = 'none';
    }

    function hideNoResults() {
        const noResultsMsg = document.getElementById('noResults');
        if (noResultsMsg) noResultsMsg.style.display = 'none';
        if (requestsGrid) requestsGrid.style.display = '';
    }

    // ========== VIEW DETAILS BUTTONS ==========
    const viewDetailsButtons = document.querySelectorAll('.btn-view-details');
    const modal = document.getElementById('requestModal');
    const closeModalBtn = document.getElementById('closeModal');
    const modalBody = document.getElementById('modalBody');

    viewDetailsButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const requestCard = btn.closest('.request-card');
            if (requestCard) {
                loadRequestDetails(requestCard);
            }
        });
    });

    closeModalBtn?.addEventListener('click', () => {
        closeModal();
    });

    modal?.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // ========== LOAD REQUEST DETAILS ==========
    function loadRequestDetails(card) {
        const title = card.querySelector('.request-title').textContent;
        const id = card.querySelector('.request-id').textContent;
        const status = card.querySelector('.status-badge').textContent;
        const vehicle = card.querySelector('.info-item:nth-child(1) .info-value').textContent;
        const date = card.querySelector('.info-item:nth-child(2) .info-value').textContent;
        const description = card.querySelector('.description-preview').textContent;
        const replyElement = card.querySelector('.mechanic-reply');
        const reply = replyElement ? replyElement.querySelector('.reply-body').textContent : '';
        const timeAgo = card.querySelector('.time-label').textContent;

        modalBody.innerHTML = `
            <div style="animation: fadeInUp 0.4s ease-out;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(240, 173, 78, 0.2);">
                    <div>
                        <h2 style="margin: 0 0 0.5rem; color: var(--text-light); font-size: 1.5rem;">${escapeHtml(title)}</h2>
                        <p style="margin: 0; color: var(--primary); font-size: 0.9rem; font-family: 'Courier New', monospace;">${escapeHtml(id)}</p>
                    </div>
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: rgba(240, 173, 78, 0.2); border-radius: 20px; color: var(--primary); font-size: 0.85rem; font-weight: 600; white-space: nowrap;">${escapeHtml(status)}</span>
                </div>

                <div style="display: grid; gap: 1.5rem; margin-bottom: 2rem;">
                    <div>
                        <span style="color: var(--primary); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 0.5rem;"><i class="fas fa-car"></i> Vozilo</span>
                        <span style="color: var(--text-light); font-size: 1rem;">${escapeHtml(vehicle)}</span>
                    </div>
                    <div>
                        <span style="color: var(--primary); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 0.5rem;"><i class="fas fa-calendar"></i> Datum Zahteva</span>
                        <span style="color: var(--text-light); font-size: 1rem;">${escapeHtml(date)}</span>
                    </div>
                    <div>
                        <span style="color: var(--primary); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 0.5rem;"><i class="fas fa-file-alt"></i> Opis</span>
                        <span style="color: var(--text-light); font-size: 1rem; line-height: 1.6; white-space: pre-wrap; word-break: break-word;">${escapeHtml(description)}</span>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 0.5rem;"><i class="fas fa-clock"></i> Poslano</span>
                        <span style="color: var(--text-light); font-size: 1rem;">${escapeHtml(timeAgo)}</span>
                    </div>
                </div>

                ${reply ? `
                    <div style="background: rgba(40, 167, 69, 0.1); border-left: 3px solid var(--success); border-radius: 8px; padding: 1rem; margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--success); font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">
                            <i class="fas fa-reply"></i>
                            Odgovora Mehaničara
                        </div>
                        <div style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; white-space: pre-wrap; word-break: break-word;">
                            ${escapeHtml(reply)}
                        </div>
                    </div>
                ` : ''}

                <button class="close-modal-btn" style="width: 100%; padding: 1rem; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #111; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 1rem;">
                    Zatvori
                </button>
            </div>
        `;

        modal?.classList.add('active');
        
        // Add close button event
        document.querySelector('.close-modal-btn')?.addEventListener('click', closeModal);
    }

    function closeModal() {
        modal?.classList.remove('active');
    }

    // ========== ESCAPE HTML ==========
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // ========== KEYBOARD SHORTCUTS ==========
    document.addEventListener('keydown', (e) => {
        // Escape = Close modal
        if (e.key === 'Escape' && modal?.classList.contains('active')) {
            closeModal();
        }
        
        // Ctrl/Cmd + F = Focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            searchInput?.focus();
        }
    });

    // ========== ANIMATE STATS ON LOAD ==========
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

    // ========== CARD ANIMATION ON LOAD ==========
    requestCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.05}s`;
    });

    // ========== PREVENT ANIMATION JANK ==========
    document.addEventListener('visibilitychange', () => {
        const animatedBg = document.querySelector('.animated-bg');
        
        if (document.hidden) {
            if (animatedBg) animatedBg.style.animationPlayState = 'paused';
        } else {
            if (animatedBg) animatedBg.style.animationPlayState = 'running';
        }
    });

    // ========== CONSOLE EASTER EGG ==========
    console.log('%c📋 My Requests Page', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
    console.log('%cKeyboard shortcuts:', 'color: #888; font-weight: bold;');
    console.log('Ctrl/Cmd + F: Focus search');
    console.log('Escape: Close modal');
    console.log('%cTotal requests loaded:', 'color: #28a745;', requestCards.length);
    console.log('%cStats:', 'color: #28a745;', {
        pending: document.getElementById('pendingCount')?.textContent,
        approved: document.getElementById('approvedCount')?.textContent,
        inProgress: document.getElementById('progressCount')?.textContent,
        completed: document.getElementById('completedCount')?.textContent
    });

});