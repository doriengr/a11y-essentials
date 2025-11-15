export default () => ({
    tabs: null,
    panels: null,
    currentLabel: '',

    init() {
        this.tabs = this.$root.querySelectorAll('[role=tab]');
        this.panels = this.$root.querySelectorAll('[role=tabpanel]');

        this.setFirstTabAsActive();
    },

    selectTab(currentLabel) {
        this.currentLabel = currentLabel;
        if (!this.panels) return;

        this.panels.forEach(panel => {
            const label = panel.id.replace('tabpanel-', '');
            if (!label || label !== this.currentLabel) {
                panel.classList.add('hidden');
            } else {
                panel.classList.remove('hidden')
            }
        });
    },

    setFirstTabAsActive() {
        if (!this.tabs || !this.panels) return;
        const label = this.tabs[0].id.replace('tab-', '');
        if (!label) return;
        this.selectTab(label);
    },

    prevTab() {
        if (!this.tabs || !this.panels) return;
        let index = Array.from(this.tabs).findIndex(tab => tab.id === `tab-${this.currentLabel}`);
        index = index <= 0 ? this.tabs.length - 1 : index - 1;
        const label = this.tabs[index].id.replace('tab-', '');
        this.selectTab(label);
    },

    nextTab() {
        if (!this.tabs || !this.panels) return;
        let index = Array.from(this.tabs).findIndex(tab => tab.id === `tab-${this.currentLabel}`);
        index = index >= this.tabs.length - 1 ? 0 : index + 1; // wrap around
        const label = this.tabs[index].id.replace('tab-', '');
        this.selectTab(label);
    },

    fistTab() {
        if (!this.tabs || !this.panels) return;
        const label = this.tabs[0].id.replace('tab-', '');
        this.selectTab(label);
    },

    lastTab() {
        if (!this.tabs || !this.panels) return;
        const last = this.tabs[this.tabs.length - 1];
        const label = last.id.replace('tab-', '');
        this.selectTab(label);
    }
});
