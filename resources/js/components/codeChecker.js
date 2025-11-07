export default (options = {}) => ({

    urlInput: document.getElementById("url"),
    urlError: document.getElementById("automatic-test-url-error"),

    submit(event) {
        event.preventDefault();

        if (!this.isURLvalid()) {
            this.urlInput?.setAttribute("aria-labelledby", "automatic-test-url-error");
            this.urlError?.classList.remove("hidden");
            return;
        }

        this.urlError?.classList.add("hidden");
        this.urlInput?.removeAttribute("aria-labelledby");
    },

    isURLvalid() {
        if (!this.urlInput || !this.urlError) return false;

        const url = this.urlInput.value.trim();
        if (!url) return false;

        try {
            const parsed = new URL(url);
            return parsed.protocol === 'https:';
        } catch (e) {
            return false;
        }
    }
});
