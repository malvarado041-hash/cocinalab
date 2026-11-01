/* Keep closed drawers out of keyboard navigation and contain focus while open. */
(() => {
    function manageDrawer({ panel, toggle, query, isOpen, background }) {
        if (!panel || !toggle) return;
        const media = window.matchMedia(query);
        let active = false;
        let previousFocus;
        const controls = () => [...panel.querySelectorAll('a[href], button, input, select, textarea, [tabindex="0"]')]
            .filter(el => !el.disabled && el.getClientRects().length && !el.closest('[inert]'));
        function sync() {
            const open = media.matches && isOpen();
            panel.inert = media.matches && !open;
            panel.setAttribute('aria-hidden', String(panel.inert));
            background.forEach(el => { if (el) el.inert = open; });
            if (open && !active) {
                previousFocus = document.activeElement;
                controls()[0]?.focus();
            } else if (!open && active) {
                (previousFocus?.isConnected ? previousFocus : toggle).focus();
            }
            active = open;
        }
        document.addEventListener('keydown', event => {
            if (!active || event.key !== 'Tab') return;
            const elements = controls();
            const first = elements[0], last = elements[elements.length - 1];
            if (!first) { event.preventDefault(); return; }
            if (event.shiftKey && (document.activeElement === first || !panel.contains(document.activeElement))) {
                event.preventDefault(); last.focus();
            } else if (!event.shiftKey && (document.activeElement === last || !panel.contains(document.activeElement))) {
                event.preventDefault(); first.focus();
            }
        });
        new MutationObserver(sync).observe(panel.id === 'sidebar' ? document.documentElement : panel, { attributes: true, attributeFilter: ['class'] });
        media.addEventListener('change', sync);
        sync();
    }
    manageDrawer({ panel: document.getElementById('sidebar'), toggle: document.getElementById('sidebar-mobile-toggle'),
        query: '(max-width: 1089.98px)', isOpen: () => document.documentElement.classList.contains('sidebar-mobile-open'),
        background: [document.getElementById('main-content')] });
    manageDrawer({ panel: document.getElementById('nav-menu'), toggle: document.querySelector('[data-nav-toggle]'),
        query: '(max-width: 768px)', isOpen: () => document.getElementById('nav-menu').classList.contains('nav-open'),
        background: [] });
})();
