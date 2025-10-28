export default () => ({
    isOpen: false,

    toggle() {
        return (this.isOpen = !this.isOpen);
    },
});
