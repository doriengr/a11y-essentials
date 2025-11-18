export default (options = {}) => ({
    csrfToken: options.csrfToken,
    states: {},
    groups: {},
    debounceTime: 500,
    routes: {
        states: options.routeStates,
        groups: options.routeGroups,
    },
    pending: {
        states: {},
        groups: {},
    },
    timers: {
        states: null,
        groups: null,
    },

    init() {
        this.states = this.parse(options.states);
        this.groups = this.parse(options.groups);

        const progressDisplay = this.$root.querySelector('#progress-display');
        progressDisplay?.classList.remove('hidden');
    },

    parse: (value) => typeof value === 'string' ? JSON.parse(value || '{}') : value || {},

    async sync(type) {
        const updates = this.pending[type];
        if (!Object.keys(updates).length) return;

        // Clear pending before request
        this.pending[type] = {};

        try {
            await fetch(this.routes[type], {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": this.csrfToken
                },
                body: JSON.stringify({ [type]: updates })
            });
        } catch {
            // Re-queue on failure
            this.pending[type] = { ...updates, ...this.pending[type] };
        }
    },

    toggleState(group, id, event) {
        const value = event.target.checked;

        if (!this.states[group]) this.states[group] = {};
        this.states[group][id] = value;

        if (!this.pending.states[group]) this.pending.states[group] = {};
        this.pending.states[group][id] = value;

        this.debounceSync("states");
    },

    toggleGroup(group, event) {
        const value = event.target.checked;

        this.groups[group] = value;
        this.pending.groups[group] = value;

        this.debounceSync("groups");
    },

    debounceSync(type) {
        clearTimeout(this.timers[type]);
        this.timers[type] = setTimeout(() => this.sync(type), this.debounceTime);
    },

    countAll() {
        return this.$root.querySelectorAll('input[type="checkbox"]').length;
    },

    countChecked() {
        return Object.values(this.states)
            .flatMap(group => Object.values(group))
            .filter(Boolean).length;
    },

    progressPercent() {
        const total = this.countAll();
        return total ? (this.countChecked() / total) * 100 : 0;
    }
});
