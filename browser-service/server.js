import express from 'express';
import puppeteer from 'puppeteer';

const app = express();
app.use(express.json({ limit: '1mb' }));

const PORT = process.env.BROWSER_SERVICE_PORT || 4001;
let browser;

async function getBrowser() {
  if (browser && browser.isConnected()) {
    return browser;
  }
  browser = await puppeteer.launch({
    headless: true,
    args: [
      '--no-sandbox',
      '--disable-setuid-sandbox',
    ],
  });
  return browser;
}

app.post('/browse', async (req, res) => {
  const { url } = req.body;

  if (!url || typeof url !== 'string') {
    return res.status(400).json({ error: 'url (string) is required' });
  }

  if (/^(https?:\/\/)?(localhost|127\.0\.0\.1|10\.|192\.168\.|172\.(1[6-9]|2\d|3[0-1]))/i.test(url)) {
    return res.status(400).json({ error: 'Forbidden target URL' });
  }

  let page;
  try {
    const b = await getBrowser();
    page = await b.newPage();

    await page.goto(url, {
      waitUntil: 'networkidle2',
      timeout: 40000,
    });

    const title = await page.title();

    const content = await page.evaluate(() => {
      const body = document.body;
      if (!body) return '';
      return body.innerText.slice(0, 20000);
    });

    await page.close();

    return res.json({
      ok: true,
      url,
      title,
      content,
    });
  } catch (err) {
    if (page) {
      try { await page.close(); } catch (e) {}
    }
    console.error('Browse error:', err);
    return res.status(500).json({
      ok: false,
      error: err.message || 'Unknown error',
    });
  }
});

async function shutdown() {
  console.log('Shutting down browser service...');
  if (browser) {
    try { await browser.close(); } catch (e) {}
  }
  process.exit(0);
}

process.on('SIGINT', shutdown);
process.on('SIGTERM', shutdown);

app.listen(PORT, () => {
  console.log(`Browser service listening on http://localhost:${PORT}`);
});
