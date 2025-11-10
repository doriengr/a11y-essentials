export default (options = {}) => ({

    loadResourceRoute: options.loadResourceRoute ?? '',
    isOpen: false,
    resourceLoaded: false,

    toggle() {
        const dialog = this.$root.querySelector('dialog');
        if (!dialog) return;
        this.isOpen = !this.isOpen;
        this.isOpen ? dialog.showModal() : dialog.close();

        if (this.isOpen) this.setFocus(dialog);
        if (!this.resourceLoaded && this.isOpen) this.loadResource();
    },

    setFocus(dialog) {
        const button = dialog.querySelector('button');
        if (!button) return;
        button.focus();
    },

    async loadResource() {
        if (!this.loadResourceRoute) return;

        const html = await fetch(this.loadResourceRoute).then(r => r.text());
        console.log(html);
        const target = document.querySelector('#target');
        if (!target) return;
        target.innerHTML = html;
        this.resourceLoaded = true;
    }
});
