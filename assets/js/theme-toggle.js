/**
 * SkillBridge - Centralized Theme Switcher Engine
 * Supports Light, Dark, System themes with synchronized UI controls & localStorage persistence.
 */
(function() {
    'use strict';

    const THEME_KEY = 'skillbridge_theme';

    function getSavedTheme() {
        return localStorage.getItem(THEME_KEY) || 'system';
    }

    function getResolvedTheme(pref) {
        if (pref === 'system') {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        return pref;
    }

    function applyTheme(themeChoice) {
        const resolved = getResolvedTheme(themeChoice);
        document.documentElement.setAttribute('data-theme', resolved);
        localStorage.setItem(THEME_KEY, themeChoice);
        document.cookie = "skillbridge_theme=" + themeChoice + "; path=/; max-age=31536000; SameSite=Lax";

        // Sync all theme selectors
        document.querySelectorAll('[data-theme-select], .theme-switcher-select').forEach(el => {
            if (el.value !== themeChoice) el.value = themeChoice;
        });

        // Update icon buttons
        document.querySelectorAll('.theme-toggle-btn').forEach(btn => {
            const icon = btn.querySelector('i');
            if (!icon) return;
            if (resolved === 'dark') {
                icon.className = 'bi bi-moon-stars-fill';
                btn.setAttribute('title', 'Switch to Light Mode');
            } else {
                icon.className = 'bi bi-sun-fill';
                btn.setAttribute('title', 'Switch to Dark Mode');
            }
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

        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: resolved, preference: themeChoice } }));
    }

    document.addEventListener('DOMContentLoaded', function() {
        const initialPref = getSavedTheme();
        applyTheme(initialPref);

        // Bind change listener to all theme selectors
        document.body.addEventListener('change', function(e) {
            const t = e.target;
            if (t && (t.hasAttribute('data-theme-select') || t.classList.contains('theme-switcher-select'))) {
                applyTheme(t.value);
            }
        });

        // Bind toggle buttons
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.theme-toggle-btn');
            if (btn) {
                const current = getSavedTheme();
                const next = current === 'dark' ? 'light' : 'dark';
                applyTheme(next);
            }
        });

        // Listen for OS theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function() {
            if (getSavedTheme() === 'system') applyTheme('system');
        });
    });

    // Public API
    window.SkillBridgeTheme = {
        set: applyTheme,
        get: getSavedTheme,
        resolved: function() { return getResolvedTheme(getSavedTheme()); }
    };
})();
