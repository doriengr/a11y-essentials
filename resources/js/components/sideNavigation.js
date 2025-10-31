export default (options = {}) => ({
    slug: options.slug ?? '',

    scrollToElement() {
        const targetElement = document.getElementById(this.slug);

        if (targetElement) {
            const topPos = targetElement.getBoundingClientRect().top + window.scrollY;
            window.scrollTo({ top: topPos, behavior: 'smooth' });
            window.location.hash = this.slug;
        }
    },
});
