import hljs from 'highlight.js/lib/core';

export default (options = {}) => ({
    loadRequirementRoute: options.loadRequirementRoute ?? '',
    isOpen: false,
    requirementLoaded: false,
    dialog: null,

    init() {
        this.dialog = this.$root.querySelector('dialog');
        if (!this.dialog) return;
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && this.isOpen) {
                this.closeDialog();
            }
        });
    },

    toggle() {
        if (!this.dialog) return;

        if (!this.isOpen) {
            this.isOpen = true;
            document.body.classList.add('overflow-y-hidden');
            this.dialog.showModal();
            this.setFocus();
            if (!this.requirementLoaded) {
                this.loadRequirement();
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

    async loadRequirement() {
        const html = await fetch(this.loadRequirementRoute).then((r) => r.text());
        const target = this.$root.querySelector('[id^="dialog-target"]');
        if (!target) return;
        target.innerHTML = html;
        this.requirementLoaded = true;

        target.querySelectorAll('pre code').forEach((block) => {
            hljs.highlightElement(block);
        });
    },

    closeDialog() {
        if (!this.dialog) return;
        this.dialog.close();
        this.isOpen = false;
        document.body.classList.remove('overflow-y-hidden');
    },
});
