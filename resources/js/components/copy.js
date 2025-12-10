export default () => ({
    isCopied: false,

    copyUrl() {
        const url = window.location;
        navigator.clipboard.writeText(url);
        this.isCopied = true;
    },

    copyCode() {
        const code = this.$root.querySelector("#code-block-for-copy");
        if (!code) return;
        navigator.clipboard.writeText(code.innerHTML);
        this.isCopied = true;
    },
});
