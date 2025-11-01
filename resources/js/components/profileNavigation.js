export default () => ({
    isOpen: false,

    init() {
        this.$watch('$store.navigation.visibleNav', (value) => {
            if (value === 'profile') return;
            this.isOpen = false;
        });
    },

    toggle() {
        this.isOpen = !this.isOpen;

        this.isOpen
            ? this.$store.navigation.toggle('profile')
            : this.$store.navigation.toggle('none');
    },

    clickOutside() {
        this.isOpen = false;
        if (this.$store.navigation.visibleNav === 'profile') {
            this.$store.navigation.toggle('none');
        }
    }
});
