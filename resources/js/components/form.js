export default (options = {}) => ({
    errorsCount: options.errorsCount ?? 0,

    init() {
        const form = this.$root;

        // summary with form errors should retrieve initial focus
        if (this.errorsCount > 0) {
            const errorSummary = document.getElementById('form-errors-summary');
            if (!errorSummary) return;
            errorSummary.focus();
        }
        form.addEventListener('submit', function (e) {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                event: 'form_submit',
                form_id: form.id,
            });

            // wait for submit to fire gtm event
            e.preventDefault();
            setTimeout(() => {
                form.submit();
            }, 100);
        });
    },
});
