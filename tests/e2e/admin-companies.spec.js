import { test, expect } from '@playwright/test';

const adminBaseUrl = process.env.ADMIN_BASE_URL || 'http://127.0.0.1:8000';
const companiesUrl = `${adminBaseUrl.replace(/\/$/, '')}/admin/companies`;

const emailField = (page) => page.getByRole('textbox', { name: /email address/i });
const passwordField = (page) => page.getByLabel(/password\*/i);
const signInButton = (page) => page.getByRole('button', { name: /sign in|login/i });

test.describe('Admin companies page', () => {
  test('requires authentication when opened directly', async ({ page }) => {
    await page.goto(companiesUrl);

    await expect(page).toHaveURL(/\/admin\/login/);
    await expect(signInButton(page)).toBeVisible();
  });

  test('loads companies page after login when credentials are provided', async ({ page }) => {
    test.skip(!process.env.ADMIN_EMAIL || !process.env.ADMIN_PASSWORD, 'ADMIN_EMAIL/ADMIN_PASSWORD not set');

    await page.goto(companiesUrl);

    if (/\/admin\/login/.test(page.url())) {
      await emailField(page).fill(process.env.ADMIN_EMAIL);
      await passwordField(page).fill(process.env.ADMIN_PASSWORD);
      await signInButton(page).click();
    }

    await expect(page).toHaveURL(/\/admin\/companies/);
    await expect(page.locator('main').getByRole('heading', { level: 1, name: /companies/i })).toBeVisible();
  });
});
