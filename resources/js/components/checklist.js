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

    init() {
        this.states = this.parse(options.states);
        this.groups = this.parse(options.groups);

        this.getTotalCount();

        const progressDisplay = this.$root.querySelector('#progress-display');
        progressDisplay?.classList.remove('hidden');
    },

    parse: (value) => typeof value === 'string' ? JSON.parse(value || '{}') : value || {},

    async sync(type) {
        const updates = this.pending[type];
        if (!Object.keys(updates).length) return;

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

        this.getTotalCount();
    },

    debounceSync(type) {
        clearTimeout(this.timers[type]);
        this.timers[type] = setTimeout(() => this.sync(type), this.debounceTime);
    },

    getTotalCount() {
        this.totalCount = 0;
        const groups = this.$root.querySelectorAll('[data-group]');
        groups.forEach(group => {
            if (group.dataset.canBeHidden) {
                this.getHideableChecklistItems(group);
            } else {
                const count = group.querySelectorAll('[id^="checklist-item-"]').length;
                this.totalCount = this.totalCount + count;
            }
        })
    },

    getHideableChecklistItems(group) {
        const name = group.dataset.group;

        if (name in this.groups && this.groups[name] === true) {
            const count = group.querySelectorAll('[id^="checklist-item-"]').length;
            this.totalCount = this.totalCount + count;
        }
    },

    countChecked() {
        return Object.values(this.states)
            .flatMap(group => Object.values(group))
            .filter(Boolean).length;
    },

    progressPercent() {
        return this.totalCount ? (this.countChecked() / this.totalCount) * 100 : 0;
    }
});
