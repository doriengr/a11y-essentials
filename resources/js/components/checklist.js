export default (options = {}) => ({
    csrfToken: options.csrfToken,
    checklistId: options.checklistId,
    states: {},
    pendingUpdates: {},
    timeout: null,
    debounceTime: 500,

    init() {
        // Collect initial checkbox states from DOM
        document.querySelectorAll("input").forEach(el => {
            this.states[el.name] = el.checked;
        });

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

        fetch(`/checklists/${this.checklistId}/toggle`, {
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
