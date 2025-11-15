export default (options = {}) => ({
    tabs: null,
    panels: null,
    currentLabel: '',

    init() {
        this.tabs = this.$root.querySelectorAll('[role=tab]');
        this.panels = this.$root.querySelectorAll('[role=tabpanel]');
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
    }
});
