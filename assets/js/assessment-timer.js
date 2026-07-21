/**
 * SkillBridge - Live Assessment Countdown Timer & Auto-Save Manager
 */

function initAssessmentTimer(durationMinutes, formId, timerDisplayId, progressBarId, resultId) {
    let totalSeconds = durationMinutes * 60;
    const form = document.getElementById(formId);
    const timerDisplay = document.getElementById(timerDisplayId);
    const progressBar = document.getElementById(progressBarId);
    const initialSeconds = totalSeconds;

    function updateDisplay() {
        const minutes = Math.floor(totalSeconds / 60);
        const seconds = totalSeconds % 60;
        const formatted = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        
        if (timerDisplay) {
            timerDisplay.textContent = formatted;
        }

        if (progressBar) {
            const percentage = (totalSeconds / initialSeconds) * 100;
            progressBar.style.width = `${percentage}%`;

            if (percentage < 20) {
                progressBar.className = 'progress-bar bg-danger progress-bar-striped progress-bar-animated';
            } else if (percentage < 50) {
                progressBar.className = 'progress-bar bg-warning progress-bar-striped progress-bar-animated';
            } else {
                progressBar.className = 'progress-bar bg-primary progress-bar-striped progress-bar-animated';
            }
        }

        if (totalSeconds <= 0) {
            clearInterval(timerInterval);
            clearInterval(autoSaveInterval);
            alert('Time is up! Your assessment answers will now be submitted automatically.');
            if (form) form.submit();
        }

        totalSeconds--;
    }

    updateDisplay();
    const timerInterval = setInterval(updateDisplay, 1000);

    // Auto-save answers every 20 seconds via AJAX
    const autoSaveInterval = setInterval(function() {
        if (!form) return;
        const formData = new FormData(form);
        formData.delete('submit_assessment');
        formData.append('auto_save', '1');
        formData.append('time_remaining', totalSeconds);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            const statusEl = document.getElementById('autoSaveStatus');
            if (statusEl) {
                statusEl.innerHTML = '<span class="text-success"><i class="bi bi-cloud-check-fill me-1"></i> Auto-saved just now</span>';
                setTimeout(() => {
                    statusEl.innerHTML = '<span class="text-muted"><i class="bi bi-cloud me-1"></i> Auto-save active</span>';
                }, 3000);
            }
        })
        .catch(err => console.log('Auto-save sync pinged'));
    }, 20000);
}
