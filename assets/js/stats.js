/**
 * STATS PAGE JAVASCRIPT
 * Charts i interaktivne statistike
 */

document.addEventListener('DOMContentLoaded', () => {

    // ========== CHART COLORS ==========
    const chartColors = {
        primary: '#f0ad4e',
        primaryDark: '#d98c00',
        info: '#17a2b8',
        success: '#28a745',
        danger: '#dc3545',
        warning: '#ffc107',
        muted: '#888',
    };

    // ========== COST DISTRIBUTION CHART ==========
    const costChartCanvas = document.getElementById('costChart');
    if (costChartCanvas && typeof chartData !== 'undefined') {
        new Chart(costChartCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Servisi', 'Modifikacije'],
                datasets: [{
                    data: [
                        chartData.costDistribution.services,
                        chartData.costDistribution.modifications
                    ],
                    backgroundColor: [
                        chartColors.primary,
                        chartColors.info
                    ],
                    borderColor: [
                        chartColors.primaryDark,
                        '#0e7a9c'
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: chartColors.primary,
                        borderWidth: 1,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed || 0;
                                return ' RSD ' + formatNumber(value);
                            }
                        }
                    }
                }
            }
        });
    }

    // ========== MONTHLY SPENDING CHART ==========
    const monthlyChartCanvas = document.getElementById('monthlyChart');
    if (monthlyChartCanvas && typeof chartData !== 'undefined') {
        const labels = chartData.monthlySpending.labels.map(month => formatMonth(month));
        const values = chartData.monthlySpending.values;

        new Chart(monthlyChartCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Mesečni troškovi',
                    data: values,
                    borderColor: chartColors.primary,
                    backgroundColor: `rgba(240, 173, 78, 0.1)`,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: chartColors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: chartColors.primaryDark,
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#e0e0e0',
                            font: {
                                size: 13,
                                weight: 'bold'
                            },
                            padding: 15,
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: chartColors.primary,
                        borderWidth: 1,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y || 0;
                                return ' RSD ' + formatNumber(value);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#888',
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return formatNumber(value) + ' RSD';
                            }
                        },
                        grid: {
                            color: 'rgba(240, 173, 78, 0.1)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: '#888',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    }

    // ========== HELPER FUNCTIONS ==========
    function formatNumber(value) {
        return new Intl.NumberFormat('sr-RS', {
            maximumFractionDigits: 0
        }).format(value);
    }

    function formatMonth(monthString) {
        const months = {
            '01': 'Jan', '02': 'Feb', '03': 'Mar',
            '04': 'Apr', '05': 'Maj', '06': 'Jun',
            '07': 'Jul', '08': 'Avg', '09': 'Sep',
            '10': 'Okt', '11': 'Nov', '12': 'Dec'
        };
        
        const [year, month] = monthString.split('-');
        return months[month] + ' \'' + year.slice(-2);
    }

    // ========== STAT CARDS ANIMATION ==========
    const summaryCards = document.querySelectorAll('.summary-card');
    summaryCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.05}s`;
    });

    // ========== STAT ROWS ANIMATION ON HOVER ==========
    const statRows = document.querySelectorAll('.stat-row');
    statRows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.03}s`;
        
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // ========== TYPE ITEMS ANIMATION ==========
    const typeItems = document.querySelectorAll('.type-item');
    typeItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.05}s`;
    });

    // ========== SCROLL TO TOP ==========
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

    // ========== BUTTON ACTIONS ==========
    const actionButtons = document.querySelectorAll('.btn-action');
    actionButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });

        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // ========== EXPORT STATS (Optional) ==========
    window.exportStats = function() {
        const statsData = {
            timestamp: new Date().toISOString(),
            chartData: chartData
        };
        
        const dataStr = JSON.stringify(statsData, null, 2);
        const dataBlob = new Blob([dataStr], { type: 'application/json' });
        const url = URL.createObjectURL(dataBlob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'stats-' + new Date().getTime() + '.json';
        link.click();
        URL.revokeObjectURL(url);
    };

    // ========== PRINT STATS ==========
    window.printStats = function() {
        window.print();
    };

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
    console.log('%c📊 Stats Page', 'font-size: 20px; font-weight: bold; color: #f0ad4e;');
    console.log('%cChart.js integracija sa interactive statistikom', 'color: #28a745; font-weight: bold;');
    console.log('Export funkcionalnost dostupna: window.exportStats()');
    console.log('Print funkcionalnost dostupna: window.printStats()');

});

// ========== PRINT STYLES ==========
const printStyle = document.createElement('style');
printStyle.textContent = `
    @media print {
        .navbar, .scroll-top, .action-buttons, .chart-legend {
            display: none !important;
        }
        
        body {
            background: #fff;
            color: #000;
        }
        
        .chart-card, .stats-card {
            page-break-inside: avoid;
            border: 1px solid #000 !important;
            background: #fff !important;
        }
        
        .page-header {
            border-bottom: 2px solid #000;
        }
    }
`;
document.head.appendChild(printStyle);