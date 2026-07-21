/**
 * SkillBridge - Chart.js Visualizations & Dashboard Analytics Engine
 */

// Helper to render Skill Gap Radar Chart
function renderSkillGapRadarChart(canvasId, skillLabels, actualLevels, targetLevels) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

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

// Helper to render Assessment Performance Bar Chart
function renderScoreBarChart(canvasId, assessmentTitles, scores) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: assessmentTitles,
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

// Helper to render Pass/Fail Doughnut Chart
function renderPassFailDoughnutChart(canvasId, passCount, failCount) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

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
