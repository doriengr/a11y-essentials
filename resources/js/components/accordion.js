import checkAuth from '../utils/checkAuth';

export default (options = {}) => ({
    id: options.id ?? '',
    isOpen: false,
    isTrackingEnabled: false,
    route: options.route ?? null,
    isAlreadyTracked: false,
    csrfToken: options.csrfToken ?? null,
    entryId: options.entryId ?? null,
    collection: options.collection ?? null,

    init() {
        // Check if user is currently logged in to enable tracking for learning progress
        checkAuth((user) => {
            if (!user || (Array.isArray(user) && user.length === 0)) {
                return;
            }

            this.isTrackingEnabled = true;
        });

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
        if (!this.csrfToken || !this.entryId || !this.collection) return;

        try {
            const response = await fetch(this.route, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify({
                    entry_id: this.entryId,
                    collection: this.collection,
                }),
            });

            if (response.ok) {
                this.isAlreadyTracked = true;
            }
        } catch (error) {
            console.error('Failed to track entry:', error);
        }
    },
});
