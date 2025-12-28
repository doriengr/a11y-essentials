import Alpine from 'alpinejs';

Alpine.store('auth', {
    callbacks: [],
    currentUser: null,
    loggedIn: null,
    requestStarted: false,
    csrfToken: null,
});
