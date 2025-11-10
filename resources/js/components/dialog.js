export default () => ({

    isOpen: false,

    toggle() {
        const dialog = this.$root.querySelector('dialog');
        if (!dialog) return;
        this.isOpen = !this.isOpen;
        this.isOpen ? dialog.showModal() : dialog.close();

        if (this.isOpen) {
            this.setFocus(dialog);
        }
    },

    setFocus(dialog) {
        const button = dialog.querySelector('button');
        console.log(button);
        if (!button) return;
        button.focus();
    }
});
