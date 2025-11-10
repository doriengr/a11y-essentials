export default (options = {}) => ({

    loadResourceRoute: options.loadResourceRoute ?? '',
    isOpen: false,
    resourceLoaded: false,
    dialog: null,

    init() {
        this.dialog = this.$root.querySelector('dialog');
        if (!this.dialog) return;
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && this.isOpen) {
                this.closeDialog();
            }
        })
    },

    toggle() {
        if (!this.dialog) return;
        this.isOpen = !this.isOpen;

        if (this.isOpen) {
            document.body.classList.add('overflow-y-hidden');
            this.dialog.showModal();
            this.setFocus();

            if (!this.resourceLoaded) {
                this.loadResource();
            }
        } else {
            this.closeDialog();
        }
    },

    setFocus() {
        if (!this.dialog) return;
        const button = this.dialog.querySelector('button');
        if (!button) return;
        button.focus();
    },

    async loadResource() {
        if (!this.loadResourceRoute) return;

        const html = await fetch(this.loadResourceRoute).then(r => r.text());
        const target = this.$root.querySelector('[id^="dialog-target"]');
        if (!target) return;
        target.innerHTML = html;
        this.resourceLoaded = true;
    },

    closeDialog() {
        if (!this.dialog) return;
        this.dialog.close()
        this.isOpen = !this.isOpen;
        document.body.classList.remove('overflow-y-hidden');
    }
});
