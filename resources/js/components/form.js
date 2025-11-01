export default (options = {}) => ({
    errorsCount: options.errorsCount ?? 0,

    init() {
        // summary with form errors should retrieve initial focus
        if (this.errorsCount > 0) {
            const errorSummary = document.getElementById('form-errors-summary');
            if (!errorSummary) return;
            errorSummary.focus();
        }
    },
});
