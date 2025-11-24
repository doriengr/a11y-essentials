export default (options = {}) => ({
    id: options.id ?? '',
    isOpen: false,
    isTrackingEnabled: options.isTrackingEnabled ?? false,
    route: options.route ?? '',
    isAlreadyTracked: false,
    csrfToken: options.csrfToken ?? '',

    init() {
        // Open if hash matches
        if (this.currentHashIsID()) this.isOpen = true;
        window.addEventListener('hashchange', () => {
            if (this.currentHashIsID()) this.isOpen = true;
        });
    },

    toggle() {
        this.isOpen = !this.isOpen;

        if (this.isOpen && this.isTrackingEnabled && this.route && !this.isAlreadyTracked) {
            this.trackVisitedStatus();
        }

        if (!this.isOpen && this.currentHashIsID()) {
            const noHashURL = window.location.href.replace(/#.*$/, '');
            window.history.replaceState('', document.title, noHashURL);
        }
    },

    currentHashIsID() {
        const hash = window.location.hash;
        return hash && hash.substring(1) === this.id;
    },

    async trackVisitedStatus() {
        const entryId = this.$root.dataset.entryId;
        const collection = this.$root.dataset.collection;

        if (!this.csrfToken || !entryId || !collection) return;

        try {
            const response = await fetch(this.route, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    entry_id: entryId,
                    entry_type: collection
                })
            });

            if (response.ok) {
                this.isAlreadyTracked = true;
            }
        } catch (error) {
            console.error('Failed to track entry:', error);
        }
    }
});
