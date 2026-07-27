/**
 * SkillBridge - Chart.js Visualizations & Dashboard Analytics Engine
 * 
 * Loaded globally via includes/footer.php so these helpers are always available
 * when PJAX-replaced pages call them. Never re-load this file per-page.
 */

/**
 * Guard: Ensure Chart.js is available before attempting any render.
 * Returns true if Chart.js is loaded and ready.
 */
function _chartReady() {
    return (typeof Chart !== 'undefined');
}

/**
 * Destroy any existing chart on a canvas element safely.
 * @param {HTMLCanvasElement} ctx
 */
function _destroyExistingChart(ctx) {
    try {
        const existing = Chart.getChart(ctx);
        if (existing) existing.destroy();
    } catch (e) {}
}

// ─────────────────────────────────────────────────────────────────────────────
// Skill Gap Radar Chart
// ─────────────────────────────────────────────────────────────────────────────
function renderSkillGapRadarChart(canvasId, skillLabels, actualLevels, targetLevels) {
    if (!_chartReady()) {
        console.warn('SkillBridge Charts: Chart.js not loaded yet for', canvasId);
        return;
    }
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    _destroyExistingChart(ctx);

    // Empty-data guard — show placeholder text on the canvas
    if (!skillLabels || skillLabels.length === 0) {
        const c2d = ctx.getContext('2d');
        if (c2d) {
            c2d.clearRect(0, 0, ctx.width, ctx.height);
            c2d.font = '14px Inter, sans-serif';
            c2d.fillStyle = '#94a3b8';
            c2d.textAlign = 'center';
            c2d.fillText('No skill data available', ctx.width / 2, ctx.height / 2);
        }
        return;
    }

    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: skillLabels,
            datasets: [
                {
                    label: 'Achieved Skill Level (1-5)',
                    data: actualLevels,
                    backgroundColor: 'rgba(99, 102, 241, 0.25)',
                    borderColor: '#6366f1',
                    borderWidth: 2,
                    pointBackgroundColor: '#4f46e5',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#4f46e5'
                },
                {
                    label: 'Required Target Level (1-5)',
                    data: targetLevels,
                    backgroundColor: 'rgba(16, 185, 129, 0.15)',
                    borderColor: '#10b981',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointBackgroundColor: '#059669',
                    pointBorderColor: '#fff'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { color: 'rgba(0,0,0,0.08)' },
                    grid: { color: 'rgba(0,0,0,0.08)' },
                    suggestedMin: 0,
                    suggestedMax: 5,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// Score / Performance Bar Chart
// ─────────────────────────────────────────────────────────────────────────────
function renderScoreBarChart(canvasId, labels, scores) {
    if (!_chartReady()) {
        console.warn('SkillBridge Charts: Chart.js not loaded yet for', canvasId);
        return;
    }
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    _destroyExistingChart(ctx);

    if (!labels || labels.length === 0) {
        const c2d = ctx.getContext('2d');
        if (c2d) {
            c2d.clearRect(0, 0, ctx.width, ctx.height);
            c2d.font = '14px Inter, sans-serif';
            c2d.fillStyle = '#94a3b8';
            c2d.textAlign = 'center';
            c2d.fillText('No assessment data yet', ctx.width / 2, ctx.height / 2);
        }
        return;
    }

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Score Percentage (%)',
                data: scores,
                backgroundColor: scores.map(s => s >= 75 ? '#10b981' : (s >= 60 ? '#3b82f6' : '#ef4444')),
                borderRadius: 8,
                barThickness: 24
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: v => v + '%' }
                },
                x: {
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// Pass / Fail Doughnut Chart
// ─────────────────────────────────────────────────────────────────────────────
function renderPassFailDoughnutChart(canvasId, passCount, failCount) {
    if (!_chartReady()) {
        console.warn('SkillBridge Charts: Chart.js not loaded yet for', canvasId);
        return;
    }
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    _destroyExistingChart(ctx);

    const total = (passCount || 0) + (failCount || 0);
    if (total === 0) {
        const c2d = ctx.getContext('2d');
        if (c2d) {
            c2d.clearRect(0, 0, ctx.width, ctx.height);
            c2d.font = '14px Inter, sans-serif';
            c2d.fillStyle = '#94a3b8';
            c2d.textAlign = 'center';
            c2d.fillText('No submission data yet', ctx.width / 2, ctx.height / 2);
        }
        return;
    }

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Passed', 'Needs Improvement'],
            datasets: [{
                data: [passCount, failCount],
                backgroundColor: ['#10b981', '#ef4444'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            cutout: '70%'
        }
    });
}
