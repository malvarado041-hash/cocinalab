/* CocinaLab - Tema claro / oscuro compartido (admin + sitio público).
 * Clave: localStorage 'theme' = 'light' | 'dark' | ausente (auto = sistema).
 * Aplica la clase 'dark' en <html>. Sin dependencias. */
(function () {
    'use strict';

    function getTheme() {
        try {
            return localStorage.getItem('theme') || 'auto';
        } catch (e) {
            return 'auto';
        }
    }

    function systemPrefersDark() {
        try {
            return window.matchMedia('(prefers-color-scheme: dark)').matches;
        } catch (e) {
            return false;
        }
    }

    function isForceLight() {
        try {
            return document.documentElement.hasAttribute('data-force-light');
        } catch (e) {
            return false;
        }
    }

    function applyTheme(mode) {
        // Login/registro siempre en modo claro: no aplicar ni guardar 'dark'.
        if (isForceLight()) {
            document.documentElement.classList.remove('dark');
            syncToggles();
            return;
        }
        try {
            if (mode === 'dark') {
                localStorage.setItem('theme', 'dark');
                document.documentElement.classList.add('dark');
            } else if (mode === 'light') {
                localStorage.setItem('theme', 'light');
                document.documentElement.classList.remove('dark');
            } else {
                localStorage.removeItem('theme');
                document.documentElement.classList.toggle('dark', systemPrefersDark());
            }
        } catch (e) {
            document.documentElement.classList.toggle('dark', mode === 'dark');
        }
        syncToggles();
    }

    function toggleTheme() {
        if (isForceLight()) return;
        applyTheme(document.documentElement.classList.contains('dark') ? 'light' : 'dark');
    }

    function syncToggles() {
        var isDark = document.documentElement.classList.contains('dark');
        document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
            var icon = btn.querySelector('[data-theme-icon]');
            btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            btn.setAttribute('title', isDark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro');
            if (icon) {
                // Preserva clases extra del icono (ej. nav-link-icon).
                var keep = Array.from(icon.classList).filter(function (c) {
                    return c !== 'fas' && c !== 'far' && c !== 'fab' &&
                        c.indexOf('fa-') !== 0 && c.indexOf('text-') !== 0;
                });
                icon.className = ['fas', isDark ? 'fa-sun' : 'fa-moon', isDark ? 'text-amber-400' : 'text-gray-500']
                    .concat(keep).join(' ');
            }
        });
        var checkbox = document.getElementById('toggleTheme');
        if (checkbox) checkbox.checked = isDark;
        document.dispatchEvent(new CustomEvent('cocinalab:theme', { detail: { dark: isDark, mode: getTheme() } }));
    }

    // Botones con data-theme-toggle alternan el tema automáticamente.
    document.addEventListener('click', function (e) {
        var btn = e.target.closest ? e.target.closest('[data-theme-toggle]') : null;
        if (btn) {
            e.preventDefault();
            toggleTheme();
        }
    });

    // Sincroniza entre pestañas y con cambios del sistema en modo auto.
    window.addEventListener('storage', function (e) {
        if (e.key === 'theme' && !isForceLight()) applyTheme(getTheme());
    });
    try {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function () {
            if (getTheme() === 'auto' && !isForceLight()) applyTheme('auto');
        });
    } catch (e) {}

    // En páginas con modo claro forzado, asegura quitar 'dark' aunque
    // otra pestaña lo haya activado antes de cargar este script.
    if (isForceLight()) {
        document.documentElement.classList.remove('dark');
    }

    document.addEventListener('DOMContentLoaded', syncToggles);

    window.CocinaTheme = {
        get: getTheme,
        apply: applyTheme,
        toggle: toggleTheme,
        sync: syncToggles,
        isDark: function () { return document.documentElement.classList.contains('dark'); }
    };
})();
