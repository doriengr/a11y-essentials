export default (options = {}) => ({
    slug: options.slug ?? '',
    addHashToUrl: options.addHashToUrl ?? false,

    scrollToElement() {
        const targetElement = document.getElementById(this.slug);

        if (targetElement) {
            setTimeout(() => {
                const topPos = targetElement.getBoundingClientRect().top + window.scrollY;
                window.scrollTo({ top: topPos, behavior: 'smooth' });

                if (this.addHashToUrl) {
                    window.location.hash = this.slug;
                }
            }, 50);
        }
    },
});
