export default () => ({
    isCopied: false,

    copy() {
        const url = window.location;
        navigator.clipboard.writeText(url);
        this.isCopied = true;
    },
});
