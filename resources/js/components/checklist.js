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
    totalCount: 0,
    checkedCount: 0,

    init() {
        this.states = this.parse(options.states);
        this.groups = this.parse(options.groups);

        this.setCounts();
        this.showProgressDisplay();

        // Open if hash matches
        window.addEventListener('hashchange', () => {
            const hash = window.location.hash.substring(1);
            this.toggleGroup(hash, true);
        });
    },

    async sync(type) {
        const updates = this.pending[type];
        if (!Object.keys(updates).length) return;

        this.pending[type] = {};

        try {
            await fetch(this.routes[type], {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify({
                    [type]: updates,
                    progress: this.progressPercent() ?? 0,
                }),
            });
        } catch {
            this.pending[type] = { ...updates, ...this.pending[type] };
        }
    },

    toggleState(group, id, event) {
        if (!this.states[group]) this.states[group] = {};
        this.states[group][id] = event.target.checked;

        if (!this.pending.states[group]) this.pending.states[group] = {};
        this.pending.states[group][id] = event.target.checked;

        this.debounceSync('states');
        event.target.checked ? this.checkedCount++ : this.checkedCount--;
    },

    toggleGroup(name, checkedState) {
        this.groups[name] = checkedState;
        this.pending.groups[name] = checkedState;

        this.debounceSync('groups');
        this.setCounts();
    },

    // Prevent users from sending too many requests to the server
    debounceSync(type) {
        clearTimeout(this.timers[type]);
        this.timers[type] = setTimeout(() => this.sync(type), this.debounceTime);
    },

    setCounts() {
        this.checkedCount = 0;
        this.totalCount = 0;

        this.$root.querySelectorAll('[data-group]').forEach((group) => {
            const name = group.dataset.group;
            const optional = group.dataset.isOptional;

            // Exclude groups from counting if:
            // They are designed to be hideable and have not been initialized
            // They exist in this.groups but are currently set to false
            if (optional && (!(name in this.groups) || this.groups[name] !== true)) {
                return;
            }

            const count = group.querySelectorAll('[id^="checklist-item-"]').length;
            this.totalCount += count;

            if (this.states[name]) {
                this.checkedCount += Object.values(this.states[name]).filter(Boolean).length;
            }
        });
    },

    getCheckedCount() {
        return Object.values(this.states)
            .flatMap((group) => Object.values(group))
            .filter(Boolean).length;
    },

    progressPercent() {
        return this.totalCount ? Math.round((this.checkedCount / this.totalCount) * 100) : 0;
    },

    parse(value) {
        return typeof value === 'string' ? JSON.parse(value || '{}') : value || {};
    },

    showProgressDisplay() {
        const progressDisplay = this.$root.querySelector('#progress-display');
        if (!progressDisplay) return;
        progressDisplay.classList.remove('hidden');
    },
});
