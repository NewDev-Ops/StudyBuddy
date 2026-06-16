import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('comingSoon', () => ({
    open: false,
    init() {
        document.addEventListener('coming-soon', () => { this.open = true; });
    }
}));

Alpine.start();

window.triggerComingSoon = () => {
    document.dispatchEvent(new CustomEvent('coming-soon'));
};
