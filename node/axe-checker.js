#!/usr/bin/env node
import puppeteer from 'puppeteer';
import axeSource from 'axe-core/axe.de.min.js';

const url = process.argv[2];
const includeAaa = process.argv[3] ?? false;

if (!url) {
    console.error('Bitte eine URL angeben');
    process.exit(1);
}

let values = ['best-practice', 'wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa'];

if (includeAaa) {
    values.push('wcag2aaa');
}

(async () => {
    const browser = await puppeteer.launch({
        executablePath: '/usr/bin/chromium-browser',
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
    });

    try {
        const page = await browser.newPage();
        await page.goto(url, { waitUntil: 'networkidle2' });

        await page.addScriptTag({ content: axeSource.source });

        const results = await page.evaluate(async (wcagValues) => {
            // eslint-disable-next-line no-undef
            return await axe.run(document, {
                runOnly: {
                    type: 'tag',
                    values: wcagValues,
                },
            });
        }, values);
        console.log(JSON.stringify(results));
    } catch (err) {
        console.error(err);
    } finally {
        await browser.close();
    }
})();
