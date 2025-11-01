import Alpine from 'alpinejs';

Alpine.store('navigation', {
    visibleNav: 'none',

    toggle(option) {
        this.visibleNav = option;
    }
})
