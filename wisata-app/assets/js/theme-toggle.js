/**
 * Theme Toggle Script
 * Handles switching between dark and light mode with localStorage persistence
 */

(function() {
    'use strict';

    // Theme constants
    const THEME_KEY = 'wisata-theme';
    const LIGHT_THEME = 'light';
    const DARK_THEME = 'dark';

    // Get saved theme from localStorage or default to system preference
    function getPreferredTheme() {
        const savedTheme = localStorage.getItem(THEME_KEY);
        if (savedTheme) {
            return savedTheme;
        }
        // Check for system preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
            return LIGHT_THEME;
        }
        return DARK_THEME;
    }

    // Apply theme to document
    function applyTheme(theme) {
        if (theme === LIGHT_THEME) {
            document.documentElement.setAttribute('data-theme', LIGHT_THEME);
        } else {
            document.documentElement.removeAttribute('data-theme');
        }
        localStorage.setItem(THEME_KEY, theme);
    }

    // Toggle between themes
    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === LIGHT_THEME ? DARK_THEME : LIGHT_THEME;
        applyTheme(newTheme);
    }

    // Initialize theme on page load (before DOM completely loads)
    applyTheme(getPreferredTheme());

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        // Get toggle button
        const themeToggle = document.getElementById('themeToggle');
        
        if (themeToggle) {
            // Add click event listener
            themeToggle.addEventListener('click', function(e) {
                e.preventDefault();
                toggleTheme();
                
                // Add rotation animation
                this.style.transform = 'scale(1.1) rotate(180deg)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 300);
            });
        }

        // Listen for system theme changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', function(e) {
                // Only auto-switch if user hasn't manually set theme
                if (!localStorage.getItem(THEME_KEY)) {
                    applyTheme(e.matches ? LIGHT_THEME : DARK_THEME);
                }
            });
        }
    });
})();
