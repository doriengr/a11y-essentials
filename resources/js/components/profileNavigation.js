import checkAuth from "../utils/checkAuth";

export default () => ({
    isOpen: false,
    loggedIn: false,
    userEmail: '',
    userInitials: '',

    init() {
        this.$watch('$store.navigation.visibleNav', (value) => {
            if (value === 'profile') return;
            this.isOpen = false;
        });

        checkAuth((user) => {
            if (! user || user === []) {
                return;
            }

            this.loggedIn = true;
            this.userEmail = user.email;
            this.userInitials = user.initials;
        });
    },

    toggle() {
        this.isOpen = !this.isOpen;

        this.isOpen ? this.$store.navigation.toggle('profile') : this.$store.navigation.toggle('none');
    },

    clickOutside() {
        this.isOpen = false;
        if (this.$store.navigation.visibleNav === 'profile') {
            this.$store.navigation.toggle('none');
        }
    },
});
