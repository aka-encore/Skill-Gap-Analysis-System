/**
 * SkillBridge - Centralized Theme Switcher Engine
 * Supports Light, Dark, System themes with synchronized UI controls & localStorage persistence.
 */
(function() {
    'use strict';

    const THEME_KEY = 'skillbridge_theme';

    function getSavedTheme() {
        if (window.SkillBridgeSessionTheme && window.SkillBridgeSessionTheme !== "") {
            return window.SkillBridgeSessionTheme;
        }
        return localStorage.getItem(THEME_KEY) || 'system';
    }

    function getResolvedTheme(pref) {
        if (pref === 'system') {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        return pref;
    }

    function applyTheme(themeChoice, saveToServer = false) {
        const resolved = getResolvedTheme(themeChoice);
        document.documentElement.setAttribute('data-theme', resolved);
        localStorage.setItem(THEME_KEY, themeChoice);
        document.cookie = "skillbridge_theme=" + themeChoice + "; path=/; max-age=31536000; SameSite=Lax";

        // Sync all theme selectors
        document.querySelectorAll('[data-theme-select], .theme-switcher-select').forEach(el => {
            if (el.value !== themeChoice) el.value = themeChoice;
        });

        // Update Charts if Chart.js is loaded
        if (window.Chart) {
            const chartDefaults = window.Chart.defaults;
            if (resolved === 'dark') {
                chartDefaults.color = '#94A3B8';
                chartDefaults.borderColor = 'rgba(255,255,255,0.06)';
            } else {
                chartDefaults.color = '#6B7280';
                chartDefaults.borderColor = '#E5E7EB';
            }
            // Re-render all charts
            Object.values(window.Chart.instances || {}).forEach(chart => {
                try { chart.update(); } catch(e) {}
            });
        }

        if (saveToServer) {
            fetch(window.BASE_URL + 'api/update-theme.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ theme: themeChoice })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) console.error('Failed to sync theme preference:', data.error);
            })
            .catch(err => console.error('Error syncing theme preference:', err));
        }

        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: resolved, preference: themeChoice } }));
    }

    document.addEventListener('DOMContentLoaded', function() {
        const initialPref = getSavedTheme();
        applyTheme(initialPref);

        // Bind change listener to all theme selectors
        document.body.addEventListener('change', function(e) {
            const t = e.target;
            if (t && (t.hasAttribute('data-theme-select') || t.classList.contains('theme-switcher-select'))) {
                applyTheme(t.value, true);
            }
        });

        // Listen for OS theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function() {
            if (getSavedTheme() === 'system') applyTheme('system');
        });
    });

    // Public API
    window.SkillBridgeTheme = {
        set: function(themeChoice) { applyTheme(themeChoice, true); },
        get: getSavedTheme,
        resolved: function() { return getResolvedTheme(getSavedTheme()); }
    };
})();
