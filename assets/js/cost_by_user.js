/**
 * COSTS BY USER PAGE JAVASCRIPT
 * Sorting, filtering, and export functionality
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

    // ========== TABLE ROW ANIMATION ==========
    const tableRows = document.querySelectorAll('.table-row');
    
    const rowObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 50);
                rowObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    tableRows.forEach(row => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        row.style.transition = 'all 0.5s ease';
        rowObserver.observe(row);
    });

    // ========== ANIMATE STATS ON LOAD ==========
    const animateValue = (element, start, end, duration) => {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            
            const value = Math.floor(progress * (end - start) + start);
            element.textContent = value.toLocaleString('sr-RS');
            
            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                element.textContent = end.toLocaleString('sr-RS');
            }
        };
        window.requestAnimationFrame(step);
    };

    // Animate stat values
    document.querySelectorAll('.stat-value').forEach(stat => {
        const text = stat.textContent.trim();
        const numMatch = text.match(/[\d,]+/);
        
        if (numMatch) {
            const value = parseInt(numMatch[0].replace(/,/g, ''));
            const suffix = text.replace(numMatch[0], '').trim();
            
            stat.textContent = '0';
            
            setTimeout(() => {
                animateValue(stat, 0, value, 2000);
                if (suffix) {
                    setTimeout(() => {
                        stat.textContent += ' ' + suffix;
                    }, 2000);
                }
            }, 500);
        }
    });

    // ========== CONSOLE EASTER EGG ==========
    console.log('%c💰 Costs Analytics Panel', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
    console.log('%cBuilt with ❤️ by Božidar AutoApp', 'color: #888;');

});

// ========== TABLE SORTING ==========
let sortDirection = {};

function sortTable(columnIndex) {
    const table = document.getElementById('costsTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Toggle sort direction
    sortDirection[columnIndex] = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
    const direction = sortDirection[columnIndex];
    
    // Update sort icons
    const headers = table.querySelectorAll('th');
    headers.forEach((header, index) => {
        const icon = header.querySelector('i');
        if (icon) {
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
}

// ========== EXPORT TO CSV ==========
function exportToCSV() {
    const table = document.getElementById('costsTable');
    const rows = Array.from(table.querySelectorAll('tr'));
    
    let csvContent = '\uFEFF'; // UTF-8 BOM for Serbian characters
    
    rows.forEach(row => {
        const cells = Array.from(row.querySelectorAll('th, td'));
        const rowData = cells.map(cell => {
            let text = cell.textContent.trim();
            // Remove avatar and extra spaces
            if (cell.querySelector('.user-avatar')) {
                text = cell.querySelector('.user-name').textContent.trim();
            }
            // Escape quotes and wrap in quotes if contains comma
            text = text.replace(/"/g, '""');
            return text.includes(',') ? `"${text}"` : text;
        });
        csvContent += rowData.join(',') + '\n';
    });
    
    // Create download link
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', `troskovi_korisnika_${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Show success notification
    showNotification('CSV fajl je uspešno preuzet!', 'success');
}

// ========== NOTIFICATION SYSTEM ==========
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
        <span>${message}</span>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 30px;
        background: ${type === 'success' ? 'rgba(40, 167, 69, 0.95)' : 'rgba(240, 173, 78, 0.95)'};
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
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.4s ease-out';
        setTimeout(() => notification.remove(), 400);
    }, 3000);
}

// Add animation keyframes
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

// ========== TABLE SEARCH FILTER ==========
function filterTable(searchTerm) {
    const table = document.getElementById('costsTable');
    const rows = table.querySelectorAll('tbody tr');
    let visibleCount = 0;
    
    searchTerm = searchTerm.toLowerCase();
    
    rows.forEach(row => {
        const username = row.querySelector('.user-name').textContent.toLowerCase();
        const cost = row.querySelector('.cost-value').textContent.toLowerCase();
        
        if (username.includes(searchTerm) || cost.includes(searchTerm)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Show message if no results
    const existingMsg = document.querySelector('.no-results-message');
    if (existingMsg) existingMsg.remove();
    
    if (visibleCount === 0) {
        const tbody = table.querySelector('tbody');
        const noResultsRow = document.createElement('tr');
        noResultsRow.className = 'no-results-message';
        noResultsRow.innerHTML = `
            <td colspan="3" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <div>Nema rezultata pretrage za "${searchTerm}"</div>
            </td>
        `;
        tbody.appendChild(noResultsRow);
    }
}

// ========== PRINT TABLE ==========
function printTable() {
    const printWindow = window.open('', '_blank');
    const table = document.getElementById('costsTable').cloneNode(true);
    
    // Remove sort icons
    table.querySelectorAll('th i').forEach(icon => icon.remove());
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html lang="sr">
        <head>
            <meta charset="UTF-8">
            <title>Troškovi po korisniku - Štampa</title>
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
                .cost-value {
                    font-weight: bold;
                    color: #f0ad4e;
                }
                .footer {
                    margin-top: 30px;
                    text-align: center;
                    color: #888;
                    font-size: 0.9em;
                }
            </style>
        </head>
        <body>
            <h1>💰 Troškovi po korisniku</h1>
            <p>Datum štampe: ${new Date().toLocaleDateString('sr-RS')}</p>
            ${table.outerHTML}
            <div class="footer">
                &copy; ${new Date().getFullYear()} Božidar AutoApp | Auto Servis
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(() => {
        printWindow.print();
    }, 250);
}

// ========== HIGHLIGHT ROW ON HOVER ==========
document.addEventListener('DOMContentLoaded', () => {
    const tableRows = document.querySelectorAll('.table-row');
    
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.boxShadow = '0 5px 20px rgba(240, 173, 78, 0.2)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
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
    
    // Escape = Clear search (if search exists)
    if (e.key === 'Escape') {
        const searchInput = document.querySelector('input[type="search"]');
        if (searchInput) {
            searchInput.value = '';
            filterTable('');
        }
    }
});

// ========== CALCULATE TOTAL ON PAGE LOAD ==========
document.addEventListener('DOMContentLoaded', () => {
    const costCells = document.querySelectorAll('.cost-value');
    let total = 0;
    
    costCells.forEach(cell => {
        const value = parseFloat(cell.textContent.replace(/[^\d.-]/g, ''));
        if (!isNaN(value)) {
            total += value;
        }
    });
    
    console.log(`%c💰 Ukupno troškova: ${total.toLocaleString('sr-RS')} RSD`, 'color: #f0ad4e; font-weight: bold; font-size: 14px;');
});

// ========== PERFORMANCE OPTIMIZATION ==========
// Pause animations when tab is not visible
document.addEventListener('visibilitychange', () => {
    const animatedBg = document.querySelector('.animated-bg');
    
    if (document.hidden) {
        if (animatedBg) animatedBg.style.animationPlayState = 'paused';
    } else {
        if (animatedBg) animatedBg.style.animationPlayState = 'running';
    }
});

// ========== TABLE ROW CLICK HIGHLIGHT ==========
document.addEventListener('DOMContentLoaded', () => {
    const rows = document.querySelectorAll('.table-row');
    
    rows.forEach(row => {
        row.addEventListener('click', function() {
            // Remove previous highlights
            rows.forEach(r => r.classList.remove('row-selected'));
            
            // Add highlight to clicked row
            this.classList.add('row-selected');
            
            // Add CSS for selected state if not exists
            if (!document.querySelector('#rowSelectStyle')) {
                const style = document.createElement('style');
                style.id = 'rowSelectStyle';
                style.textContent = `
                    .row-selected {
                        background: rgba(240, 173, 78, 0.15) !important;
                        border-left: 3px solid var(--primary) !important;
                    }
                `;
                document.head.appendChild(style);
            }
        });
    });
});