export default (options = {}) => ({
    route: options.route,
    csrfToken: options.csrfToken,
    states: {},
    pendingUpdates: {},
    timeout: null,
    debounceTime: 500,

    init() {
        this.states = typeof options.states === 'string'
            ? JSON.parse(options.states)
            : (options.states || {});

        const progressDisplay = this.$root.querySelector('#progress-display');
        if (!progressDisplay) return;
        progressDisplay.classList.remove('hidden');
    },

    toggleChecklistItem(group, id, event) {
        const value = event.target.checked;

        if (!this.states[group]) {
            this.states[group] = {};
        }

        this.states[group][id] = value;

        if (!this.pendingUpdates[group]) {
            this.pendingUpdates[group] = {};
        }

        this.pendingUpdates[group][id] = value;

        clearTimeout(this.timeout);
        this.timeout = setTimeout(() => this.sync(), this.debounceTime);
    },

    sync() {
        if (!Object.keys(this.pendingUpdates).length) return;

        const updates = this.pendingUpdates;
        this.pendingUpdates = {};

        fetch(this.route, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": this.csrfToken
            },
            body: JSON.stringify({ updates })
        }).catch(() => {
            this.pendingUpdates = {
                ...updates,
                ...this.pendingUpdates
            };
        });
    },

    countAll() {
        return this.$root.querySelectorAll('input').length;
    },

    countChecked() {
        return Object.values(this.states)
            .flatMap(group => Object.values(group))
            .filter(v => v).length;
    },

    progressPercent() {
        const total = this.countAll();
        return total ? (this.countChecked() / total) * 100 : 0;
    }
});
