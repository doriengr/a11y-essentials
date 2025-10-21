const $formWrappers = document.querySelectorAll('.js-form-wrapper');

$formWrappers.forEach(($formWrapper) => {
    /**
     * @type {HTMLFormElement | null}
     */
    const $form = $formWrapper.querySelector('.js-form');

    if (!$form) {
        console.error('Form not found');
        return;
    }

    let formState = {
        error: false,
        errors: [],
        sending: false,
        success: false,
    };

    function updateFormUI() {
        const $successMessage = $formWrapper.querySelector('.js-form-success-message');
        const $errorMessage = $formWrapper.querySelector('.js-form-error-message');

        if (!$form) {
            return;
        }

        if (formState.success) {
            $successMessage?.classList.remove('hidden');
            $form.classList.add('hidden');
        } else {
            $successMessage?.classList.add('hidden');
            $form.classList.remove('hidden');
        }

        if (formState.error) {
            $errorMessage?.classList.remove('hidden');
        } else {
            $errorMessage?.classList.add('hidden');
        }
    }

    function clearErrors() {
        const $errorContainers = $form?.querySelectorAll('.js-form-error');

        $errorContainers?.forEach(($errorContainer) => {
            $errorContainer.classList.add('hidden');
        });
    }

    function displayError(fieldName, error) {
        const $errorContainer = $form?.querySelector(`.js-form-error-${fieldName}`);
        const $errorMessageContainer = $errorContainer?.querySelector('.js-form-field-error-message');

        $errorContainer?.classList.remove('hidden');
        if ($errorMessageContainer) {
            $errorMessageContainer.innerHTML = error;
        }
    }

    function handleSuccess() {
        if (!$form) {
            return;
        }

        formState.errors = [];
        formState.success = true;
        formState.error = false;
        formState.sending = false;
        $form.reset();
    }

    function handleError(errors) {
        formState.sending = false;
        formState.error = true;
        formState.success = false;
        formState.errors = errors;

        for (const fieldName in errors) {
            displayError(fieldName, errors[fieldName]);
        }
    }

    async function sendForm() {
        if (formState.sending || !$form) {
            return;
        }

        formState.sending = true;
        updateFormUI();
        clearErrors();

        fetch($form.action, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            method: 'POST',
            body: new FormData($form),
        })
            .then((res) => res.json())
            .then((json) => {
                if (json['success']) {
                    handleSuccess();
                    updateFormUI();
                }
                if (json['error']) {
                    handleError(json['error']);
                    updateFormUI();
                }
            })
            .catch((err) => {
                console.error(err);
                formState.sending = false;
                updateFormUI();
            });
    }

    $form.addEventListener('submit', (event) => {
        event.preventDefault();
        sendForm();
    });
});
