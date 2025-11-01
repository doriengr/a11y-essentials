export default () => ({
    isOpen: false,
    isSubnavOpen: false,

    init() {
        this.$watch('$store.navigation.visibleNav', (value) => {
            if (value === 'main') return;
            this.isOpen = false;
            this.isSubnavOpen = false;
        });
    },

    toggle() {
        this.isOpen = !this.isOpen;

        this.isOpen
            ? this.$store.navigation.toggle('main')
            : this.$store.navigation.toggle('none');
    },

    toggleSubnav() {
        this.isSubnavOpen = !this.isSubnavOpen;

        this.isSubnavOpen
            ? this.$store.navigation.toggle('main')
            : this.$store.navigation.toggle('none');
    },

    clickOutside() {
        this.isOpen = false;
        if (this.$store.navigation.visibleNav === 'main') {
            this.$store.navigation.toggle('none');
        }
    }
});
