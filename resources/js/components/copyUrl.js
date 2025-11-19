export default () => ({

    isCopied: false,

    copy(event) {
        const url = window.location;
        navigator.clipboard.writeText(url);
        this.isCopied = true;
    },
});
