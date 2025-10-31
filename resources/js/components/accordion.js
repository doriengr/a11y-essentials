export default (options = {}) => ({
    id: options.id ?? '',
    isOpen: false,

    init() {
        if (this.currentHashIsID()) {
            this.isOpen = true;
            setTimeout(() => {
                const topPos = this.$root.getBoundingClientRect().top + window.scrollY;
                window.scrollTo({ top: topPos, behavior: 'smooth' });
            }, 50);
        }

        window.addEventListener('hashchange', () => {
            if (this.currentHashIsID()) this.isOpen = true;
        });
    },

    toggle() {
        this.isOpen = !this.isOpen;

        if (!this.isOpen && this.currentHashIsID()) {
            const noHashURL = window.location.href.replace(/#.*$/, '');
            window.history.replaceState('', document.title, noHashURL);
        }
    },

    currentHashIsID() {
        const hash = window.location.hash;
        if (!hash) return false;
        return hash.substring(1) === this.id;
    },
});
