export default (options = {}) => ({

    action: options.action ?? undefined,
    urlInput: document.getElementById("url"),
    urlError: document.getElementById("automatic-test-url-error"),
    url: "",

    submit(event) {
        event.preventDefault();

        if (!this.isURLvalid()) {
            this.urlInput?.setAttribute("aria-labelledby", "automatic-test-url-error");
            this.urlError?.classList.remove("hidden");
            return;
        }

        this.urlError?.classList.add("hidden");
        this.urlInput?.removeAttribute("aria-labelledby");
        if (!this.action) return;

        fetch(this.action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ url: this.url })
        })
        .then(r => r.json())
        .then(results => console.log(results))
        .catch(err => console.error(err));
    },

    isURLvalid() {
        if (!this.urlInput || !this.urlError) return false;

        this.url = this.urlInput.value.trim();
        if (!this.url) return false;

        try {
            const parsed = new URL(this.url);
            return parsed.protocol === 'https:';
        } catch (e) {
            return false;
        }
    }
});
