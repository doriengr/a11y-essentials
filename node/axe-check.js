#!/usr/bin/env node
import puppeteer from 'puppeteer';
import axeSource from 'axe-core';

const url = process.argv[2];

if (!url) {
  console.error('Bitte eine URL angeben');
  process.exit(1);
}

(async () => {
    const browser = await puppeteer.launch({
        executablePath: '/usr/bin/chromium-browser',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });

  try {
    const page = await browser.newPage();
    await page.goto(url, { waitUntil: 'networkidle2' });

    // axe-core einfügen
    await page.addScriptTag({ content: axeSource.source });

    const results = await page.evaluate(async () => {
      return await axe.run(document, {
        runOnly: {
          type: 'tag',
          values: ['wcag2a', 'wcag2aa']
        }
      });
    });

    console.log(JSON.stringify(results));
  } catch (err) {
    console.error(err);
  } finally {
    await browser.close();
  }
})();
