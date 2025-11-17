export default (options = {}) => ({
    route: options.route,
    csrfToken: options.csrfToken,
    states: {},
    pendingUpdates: {},
    timeout: null,
    debounceTime: 500,

    init() {
        if (typeof options.states === 'string') {
            this.states = JSON.parse(options.states);
        } else {
            this.states = options.states || {};
        }
    },

    toggle(name, event) {
        if (!name || !event) return;
        const value = event.target.checked;

        // Update local state
        this.states[name] = value;
        this.pendingUpdates[name] = value;

        // Debounce trigger
        clearTimeout(this.timeout);
        this.timeout = setTimeout(() => {
            this.sync();
        }, this.debounceTime);
    },

    sync() {
        if (Object.keys(this.pendingUpdates).length === 0) return;

        const updates = this.pendingUpdates;
        this.pendingUpdates = {};

        fetch(this.route, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            },
            body: JSON.stringify({ updates })
        })
        .catch(() => {
            // On error restore pendingUpdates
            this.pendingUpdates = Object.assign(this.pendingUpdates, updates);
        });
    }
});
