export default (callback) => {
    let auth = window.Alpine.store('auth');
    let logged_in = auth.logged_in;
    let user = auth.currentUser;

    // If status is already known
    if (typeof logged_in !== 'undefined' && logged_in !== null) {
        if (logged_in === true) {
            callback(user);
        }
        return;
    }

    // Register callback for when auth is confirmed
    auth.callbacks.push(callback);

    // Prevent duplicate requests
    if (auth.requestStarted === true) {
        return;
    }

    auth.requestStarted = true;

    fetch('/auth/logged-in-user', {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json',
        },
    })
        .then(async (res) => {
            if (!res.ok) {
                console.log('Error on async auth status verification.');
                return;
            }

            const response = await res.json();
            let result = response.data;

            if (typeof result.logged_in === 'undefined') {
                return;
            }

            auth.logged_in = result.logged_in;

            if (result.logged_in === true) {
                auth.currentUser = result.user;

                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push({
                    event: 'setUserId',
                    user_id: auth.currentUser.id,
                });

                // Execute queued callbacks
                auth.callbacks.forEach((func) => func(result.user));
            }
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            auth.requestStarted = false;
            auth.callbacks = [];
        });
};
