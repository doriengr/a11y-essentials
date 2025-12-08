import Alpine from 'alpinejs';

Alpine.store('auth', {
    callbacks: [],
    currentUser: null,
    logged_in: null,
    requestStarted: false
});
