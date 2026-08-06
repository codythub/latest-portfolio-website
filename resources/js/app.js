

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import './post-editor';

window.Alpine = Alpine;
window.Chart = Chart;

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const publicThemePreference = window.matchMedia('(prefers-color-scheme: dark)');

function preferredPublicTheme() {
    let savedTheme = null;

    try {
        savedTheme = window.localStorage.getItem('public-theme');
    } catch (error) {
        savedTheme = null;
    }

    if (savedTheme === 'dark' || savedTheme === 'light') {
        return savedTheme;
    }

    return publicThemePreference.matches ? 'dark' : 'light';
}

function applyPublicTheme(theme) {
    const isDark = theme === 'dark';
    const moonIconUrl = '/images/icons/dark-mode.svg';
    const sunIconUrl = '/images/icons/sun.svg';

    document.documentElement.classList.toggle('public-dark', isDark);
    document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';

    document.querySelectorAll('[data-public-theme-toggle]').forEach((button) => {
        button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
        button.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');

        const icon = button.querySelector('img');

        if (icon) {
            icon.src = isDark ? sunIconUrl : moonIconUrl;
        }
    });
}

function savePublicTheme(theme) {
    try {
        window.localStorage.setItem('public-theme', theme);
    } catch (error) {
        return;
    }
}

function initPublicTheme() {
    applyPublicTheme(preferredPublicTheme());

    document.querySelectorAll('[data-public-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const nextTheme = document.documentElement.classList.contains('public-dark') ? 'light' : 'dark';

            savePublicTheme(nextTheme);
            applyPublicTheme(nextTheme);
        });
    });

    const handleSystemThemeChange = () => {
        try {
            if (window.localStorage.getItem('public-theme')) {
                return;
            }
        } catch (error) {
            return;
        }

        applyPublicTheme(preferredPublicTheme());
    };

    if (typeof publicThemePreference.addEventListener === 'function') {
        publicThemePreference.addEventListener('change', handleSystemThemeChange);
    } else if (typeof publicThemePreference.addListener === 'function') {
        publicThemePreference.addListener(handleSystemThemeChange);
    }
}

function finishPublicLoading() {
    if (! document.documentElement.classList.contains('public-loading')) {
        return;
    }

    requestAnimationFrame(() => {
        document.documentElement.classList.remove('public-loading');
        document.documentElement.classList.add('public-ready');
    });
}

function initPublicMotion() {
    if (! document.documentElement.classList.contains('js')) {
        return;
    }

    const revealTargets = [
        ...document.querySelectorAll('.motion-reveal'),
    ];

    const uniqueTargets = [...new Set(revealTargets)].filter((target) => {
        return target instanceof HTMLElement;
    });

    if (uniqueTargets.length === 0) {
        return;
    }

    if (prefersReducedMotion || ! ('IntersectionObserver' in window)) {
        uniqueTargets.forEach((target) => {
            target.classList.add('is-visible');
        });

        return;
    }

    document.documentElement.classList.add('motion-ready');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (! entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, {
        rootMargin: '0px 0px -8% 0px',
        threshold: 0.12,
    });

    uniqueTargets.forEach((target) => {
        target.classList.add('motion-reveal');
        observer.observe(target);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initPublicTheme();
        finishPublicLoading();
        initPublicMotion();
    }, { once: true });
} else {
    initPublicTheme();
    finishPublicLoading();
    initPublicMotion();
}

Alpine.start();
