import { chromium } from 'playwright';
import path from 'path';

(async () => {
    const browser = await chromium.launch();
    const page = await browser.newPage();
    const filePath = 'file:///' + path.resolve('flows.html').replace(/\\/g, '/');
    await page.goto(filePath, { waitUntil: 'networkidle' });
    await page.pdf({
        path: 'Textile_ERP_Flows.pdf',
        format: 'A4',
        margin: { top: '20px', right: '20px', bottom: '20px', left: '20px' }
    });
    await browser.close();
    console.log('PDF created successfully at Textile_ERP_Flows.pdf');
})();
