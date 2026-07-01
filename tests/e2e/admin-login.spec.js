import { test, expect } from '@playwright/test';

test.describe('Admin login page', () => {
  const emailField = (page) => page.getByRole('textbox', { name: /email address/i });
  const passwordField = (page) => page.getByLabel(/password\*/i);
  const signInButton = (page) => page.getByRole('button', { name: /sign in|login/i });

  test('loads login form fields and submit button', async ({ page }) => {
    await page.goto('/admin/login');

    await expect(page).toHaveURL(/\/admin\/login/);
    await expect(emailField(page)).toBeVisible();
    await expect(passwordField(page)).toBeVisible();
    await expect(signInButton(page)).toBeVisible();
  });

  test('shows validation/errors for invalid credentials', async ({ page }) => {
    await page.goto('/admin/login');

    await emailField(page).fill('invalid@example.com');
    await passwordField(page).fill('wrong-password');
    await signInButton(page).click();

    // Filament/Laravel commonly returns either a validation block or an auth error toast/message.
    await expect(page.getByText(/invalid|failed|credentials|whoops|error/i)).toBeVisible();
    await expect(page).toHaveURL(/\/admin\/login/);
  });

  test('can login with valid credentials when env vars are provided', async ({ page }) => {
    test.skip(!process.env.ADMIN_EMAIL || !process.env.ADMIN_PASSWORD, 'ADMIN_EMAIL/ADMIN_PASSWORD not set');

    await page.goto('/admin/login');
    await emailField(page).fill(process.env.ADMIN_EMAIL);
    await passwordField(page).fill(process.env.ADMIN_PASSWORD);
    await signInButton(page).click();

    await expect(page).toHaveURL(/\/admin(?!\/login)/);
  });
});
