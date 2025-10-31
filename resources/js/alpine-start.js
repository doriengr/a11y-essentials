import hljs from 'highlight.js/lib/core';
import xml from 'highlight.js/lib/languages/xml';

hljs.registerLanguage('html', xml);

window.addEventListener('load', () => {
    if (window.Alpine) {
        window.Alpine.start();
        hljs.highlightAll();
    }
});
