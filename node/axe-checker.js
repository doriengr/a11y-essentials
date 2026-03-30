import puppeteer from 'puppeteer-extra';
import stealthPlugin from 'puppeteer-extra-plugin-stealth';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const axePath = path.join(__dirname, 'node_modules/axe-core/axe.de.js');
const axeSource = fs.readFileSync(axePath, 'utf8');

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
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-blink-features=AutomationControlled',
        ],
    });
    puppeteer.use(stealthPlugin());
    const page = await browser.newPage();
    await page.setUserAgent(
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122 Safari/537.36',
    );

    try {
        await page.goto(url, { waitUntil: 'domcontentloaded' });
        await page.waitForSelector('body');

        await page.evaluate((source) => {
            const script = document.createElement('script');
            script.textContent = source;
            document.head.appendChild(script);
        }, axeSource);

        await page.waitForFunction(() => typeof window.axe !== 'undefined');

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
        console.error(
            JSON.stringify({
                error: err.message,
                stack: err.stack,
            }),
        );
        process.exit(1);
    } finally {
        await browser.close();
    }
})();
