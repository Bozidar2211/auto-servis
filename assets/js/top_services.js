/**
 * TOP SERVICES PAGE JAVASCRIPT
 * Animacije, sortiranje, export i interaktivne funkcije
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

    // ========== ANIMATE SUMMARY VALUES ==========
    const animateValue = (element, start, end, duration) => {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            
            const value = Math.floor(progress * (end - start) + start);
            element.textContent = value.toLocaleString('sr-RS');
            
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    };

    // Animate all summary values
    document.querySelectorAll('.summary-value').forEach(stat => {
        const text = stat.textContent.trim();
        // Only animate if it's a number
        if (/^\d+$/.test(text)) {
            const value = parseInt(text);
            stat.textContent = '0';
            
            setTimeout(() => {
                animateValue(stat, 0, value, 2000);
            }, 500);
        }
    });

    // ========== PROGRESS BAR ANIMATION ==========
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const progressObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progressBar = entry.target;
                const percentage = progressBar.getAttribute('data-percentage');
                
                // Animate from 0 to target percentage
                progressBar.style.width = '0%';
                setTimeout(() => {
                    progressBar.style.width = percentage + '%';
                }, 100);
                
                progressObserver.unobserve(progressBar);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.progress-bar').forEach(bar => {
        progressObserver.observe(bar);
    });

    // ========== RANKING ITEM ANIMATIONS ==========
    const rankingObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                rankingObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.ranking-item').forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = 'all 0.5s ease';
        rankingObserver.observe(item);
    });

    // ========== CONSOLE EASTER EGG ==========
    console.log('%c🏆 Top Services Analytics', 'font-size: 20px; font-weight: bold; color: #ffd700;');
    console.log('%cBuilt with ❤️ by Božidar AutoApp', 'color: #888;');

});

// ========== TOGGLE TABLE VIEW ==========
let tableVisible = false;

window.toggleTableView = function() {
    const table = document.getElementById('dataTable');
    const btn = document.querySelector('.btn-toggle');
    
    if (tableVisible) {
        table.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-eye"></i><span>Prikaži tabelu</span>';
        tableVisible = false;
    } else {
        table.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-eye-slash"></i><span>Sakrij tabelu</span>';
        tableVisible = true;
        
        // Animate table rows
        const rows = table.querySelectorAll('.table-row');
        rows.forEach((row, index) => {
            row.style.animation = 'none';
            setTimeout(() => {
                row.style.animation = `fadeInUp 0.3s ease-out ${index * 0.05}s backwards`;
            }, 10);
        });
    }
};

// ========== TABLE SORTING ==========
let sortDirection = {};

window.sortTable = function(columnIndex) {
    const table = document.querySelector('.data-table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Toggle sort direction
    sortDirection[columnIndex] = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
    const direction = sortDirection[columnIndex];
    
    // Update sort icons
    const headers = table.querySelectorAll('th');
    headers.forEach((header, index) => {
        const icon = header.querySelector('i');
        if (icon && icon.classList.contains('fa-sort')) {
            if (index === columnIndex) {
                icon.className = direction === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
            } else {
                icon.className = 'fas fa-sort';
            }
        }
    });
    
    // Sort rows
    rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();
        
        // Check if values are numbers
        const aNum = parseFloat(aValue.replace(/[^\d.-]/g, ''));
        const bNum = parseFloat(bValue.replace(/[^\d.-]/g, ''));
        
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return direction === 'asc' ? aNum - bNum : bNum - aNum;
        }
        
        // String comparison
        return direction === 'asc' 
            ? aValue.localeCompare(bValue, 'sr-RS')
            : bValue.localeCompare(aValue, 'sr-RS');
    });
    
    // Re-append sorted rows
    rows.forEach(row => tbody.appendChild(row));
    
    // Add animation to sorted rows
    rows.forEach((row, index) => {
        row.style.animation = 'none';
        setTimeout(() => {
            row.style.animation = `fadeInUp 0.3s ease-out ${index * 0.03}s backwards`;
        }, 10);
    });
    
    showNotification('Tabela sortirana!', 'success');
};

// ========== EXPORT TO CSV ==========
window.exportToCSV = function() {
    const rankingItems = document.querySelectorAll('.ranking-item');
    
    if (rankingItems.length === 0) {
        showNotification('Nema podataka za export!', 'error');
        return;
    }
    
    let csvContent = '\uFEFF'; // UTF-8 BOM for Serbian characters
    csvContent += 'Rang,Opis servisa,Broj izvršenih,Procenat\n';
    
    rankingItems.forEach((item, index) => {
        const rank = index + 1;
        const serviceName = item.querySelector('.service-name').textContent.trim();
        const stats = item.querySelectorAll('.stat-item span');
        const count = stats[0].textContent.trim();
        const percentage = stats[1].textContent.trim();
        
        // Clean service name
        const cleanName = serviceName.replace(/[\r\n]+/g, ' ').trim();
        
        csvContent += `${rank},"${cleanName}",${count},${percentage}\n`;
    });
    
    // Create download link
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', `top_servisi_${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showNotification('CSV fajl uspešno preuzet!', 'success');
};

// ========== PRINT TABLE ==========
window.printTable = function() {
    const rankingItems = document.querySelectorAll('.ranking-item');
    
    if (rankingItems.length === 0) {
        showNotification('Nema podataka za štampu!', 'error');
        return;
    }
    
    let printContent = `
        <!DOCTYPE html>
        <html lang="sr">
        <head>
            <meta charset="UTF-8">
            <title>Top Servisi - Štampa</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                }
                h1 {
                    color: #f0ad4e;
                    border-bottom: 3px solid #f0ad4e;
                    padding-bottom: 10px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 12px;
                    text-align: left;
                }
                th {
                    background-color: #f0ad4e;
                    color: white;
                    font-weight: bold;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                .rank-badge {
                    font-weight: bold;
                    padding: 5px 10px;
                    border-radius: 5px;
                    display: inline-block;
                }
                .rank-1 { background-color: #ffd700; color: #111; }
                .rank-2 { background-color: #c0c0c0; color: #111; }
                .rank-3 { background-color: #cd7f32; color: #111; }
                .footer {
                    margin-top: 30px;
                    text-align: center;
                    color: #888;
                    font-size: 0.9em;
                }
            </style>
        </head>
        <body>
            <h1>🏆 Top Tipovi Servisa</h1>
            <p>Datum štampe: ${new Date().toLocaleDateString('sr-RS')}</p>
            <table>
                <thead>
                    <tr>
                        <th>Rang</th>
                        <th>Opis servisa</th>
                        <th>Broj izvršenih</th>
                        <th>Procenat</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    rankingItems.forEach((item, index) => {
        const rank = index + 1;
        const rankClass = rank <= 3 ? `rank-${rank}` : '';
        const serviceName = item.querySelector('.service-name').textContent.trim();
        const stats = item.querySelectorAll('.stat-item span');
        const count = stats[0].textContent.trim();
        const percentage = stats[1].textContent.trim();
        
        printContent += `
            <tr>
                <td><span class="rank-badge ${rankClass}">#${rank}</span></td>
                <td>${serviceName}</td>
                <td>${count}</td>
                <td>${percentage}</td>
            </tr>
        `;
    });
    
    printContent += `
                </tbody>
            </table>
            <div class="footer">
                &copy; ${new Date().getFullYear()} Božidar AutoApp | Auto Servis
            </div>
        </body>
        </html>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(() => {
        printWindow.print();
    }, 250);
    
    showNotification('Pripremljeno za štampu!', 'success');
};

// ========== NOTIFICATION SYSTEM ==========
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
    notification.innerHTML = `
        <i class="fas fa-${icon} me-2"></i>
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
        animation: slideInRight 0.4s ease-out;
        backdrop-filter: blur(10px);
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.4s ease-out';
        setTimeout(() => notification.remove(), 400);
    }, 3000);
}

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

// ========== RANKING ITEM HOVER EFFECTS ==========
document.addEventListener('DOMContentLoaded', () => {
    const rankingItems = document.querySelectorAll('.ranking-item');
    
    rankingItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            const badge = this.querySelector('.rank-badge');
            if (badge) {
                badge.style.transform = 'scale(1.1) rotate(5deg)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            const badge = this.querySelector('.rank-badge');
            if (badge) {
                badge.style.transform = 'scale(1) rotate(0deg)';
            }
        });
    });
});

// ========== KEYBOARD SHORTCUTS ==========
document.addEventListener('keydown', (e) => {
    // Ctrl/Cmd + E = Export CSV
    if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
        e.preventDefault();
        exportToCSV();
    }
    
    // Ctrl/Cmd + P = Print
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        printTable();
    }
    
    // T = Toggle table
    if (e.key === 't' || e.key === 'T') {
        if (!e.ctrlKey && !e.metaKey) {
            toggleTableView();
        }
    }
});

// ========== SEARCH/FILTER FUNCTIONALITY ==========
window.filterServices = function(searchTerm) {
    const rankingItems = document.querySelectorAll('.ranking-item');
    const lowerSearch = searchTerm.toLowerCase();
    let visibleCount = 0;
    
    rankingItems.forEach(item => {
        const serviceName = item.querySelector('.service-name').textContent.toLowerCase();
        
        if (serviceName.includes(lowerSearch)) {
            item.style.display = 'flex';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    if (visibleCount === 0) {
        showNotification('Nema rezultata pretrage', 'error');
    }
};

// ========== PERFORMANCE OPTIMIZATION ==========
document.addEventListener('visibilitychange', () => {
    const animatedBg = document.querySelector('.animated-bg');
    
    if (document.hidden) {
        if (animatedBg) animatedBg.style.animationPlayState = 'paused';
    } else {
        if (animatedBg) animatedBg.style.animationPlayState = 'running';
    }
});

// ========== HIGHLIGHT TOP 3 ==========
document.addEventListener('DOMContentLoaded', () => {
    const rankBadges = document.querySelectorAll('.rank-badge');
    
    rankBadges.forEach((badge, index) => {
        if (index < 3) {
            // Add special glow effect for top 3
            badge.style.animation = 'badgePulse 2s ease-in-out infinite';
            badge.style.animationDelay = `${index * 0.2}s`;
        }
    });
    
    // Add CSS for badge pulse
    if (!document.querySelector('#badgePulseStyles')) {
        const style = document.createElement('style');
        style.id = 'badgePulseStyles';
        style.textContent = `
            @keyframes badgePulse {
                0%, 100% {
                    box-shadow: 0 5px 20px rgba(255, 215, 0, 0.4);
                }
                50% {
                    box-shadow: 0 5px 30px rgba(255, 215, 0, 0.6);
                }
            }
        `;
        document.head.appendChild(style);
    }
});

// ========== LOG KEYBOARD SHORTCUTS ==========
console.log('%cKeyboard Shortcuts:', 'color: #f0ad4e; font-weight: bold; font-size: 14px;');
console.log('Ctrl/Cmd + E: Export CSV');
console.log('Ctrl/Cmd + P: Print');
console.log('T: Toggle Table View');