/* CocinaLab - Mostrar/ocultar contraseña (icono ojo). Sin dependencias.
 * Uso: <button type="button" data-password-toggle data-target="#id-input"><i class="fas fa-eye"></i></button> */
(function () {
    'use strict';

    document.addEventListener('click', function (e) {
        var btn = e.target.closest ? e.target.closest('[data-password-toggle]') : null;
        if (!btn) return;
        e.preventDefault();

        var input = null;
        var target = btn.getAttribute('data-target');
        if (target) {
            try {
                input = document.querySelector(target);
            } catch (err) {
                input = null;
            }
        }
        if (!input) return;
        if (input.type !== 'password' && input.type !== 'text') return;

        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';

        var icon = btn.querySelector('i');
        if (icon) {
            icon.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
        }
        var label = show ? 'Ocultar contraseña' : 'Mostrar contraseña';
        btn.setAttribute('aria-label', label);
        btn.setAttribute('title', label);
    });
})();
