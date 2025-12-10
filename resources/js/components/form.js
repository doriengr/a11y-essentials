export default (options = {}) => ({
    errorsCount: options.errorsCount ?? 0,

    init() {
        // summary with form errors should retrieve initial focus
        if (this.errorsCount > 0) {
            const errorSummary = document.getElementById('form-errors-summary');
            if (!errorSummary) return;
            errorSummary.focus();
        }

        this.$root.addEventListener('submit', function (e) {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                event: 'form_submit',
                form_id: this.$root.id,
            });

            // wait for submit to fire gtm event
            e.preventDefault();
            setTimeout(() => {
                this.$root.submit();
            }, 100);
        });
    },
});
