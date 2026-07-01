import { test, expect } from '@playwright/test';

const adminBaseUrl = process.env.ADMIN_BASE_URL || 'http://127.0.0.1:8000';
const companiesUrl = `${adminBaseUrl.replace(/\/$/, '')}/admin/companies`;

const emailField = (page) => page.getByRole('textbox', { name: /email address/i });
const passwordField = (page) => page.getByLabel(/password\*/i);
const signInButton = (page) => page.getByRole('button', { name: /sign in|login/i });
const companyNameInput = (page) => page.getByRole('textbox', { name: /^name\*?$/i });
const companyCodeInput = (page) => page.getByRole('textbox', { name: /^code\*?$/i });

async function firstVisible(locators) {
  for (const locator of locators) {
    if (await locator.count()) {
      return locator.first();
    }
  }

  return null;
}

async function loginIfNeeded(page) {
  await page.goto(companiesUrl);

  if (/\/admin\/login/.test(page.url())) {
    if (!process.env.ADMIN_EMAIL || !process.env.ADMIN_PASSWORD) {
      test.skip(true, 'ADMIN_EMAIL/ADMIN_PASSWORD not set');
    }

    await emailField(page).fill(process.env.ADMIN_EMAIL);
    await passwordField(page).fill(process.env.ADMIN_PASSWORD);
    await signInButton(page).click();
  }

  await expect(page).toHaveURL(/\/admin\/companies/);
}

async function openCreateForm(page) {
  await ensureOnCompaniesList(page);
  await expect(page.getByRole('heading', { level: 1, name: /companies/i })).toBeVisible();

  let createAction = await firstVisible([
    page.locator('a[href$="/admin/companies/create"]'),
    page.getByRole('link', { name: /new\s+company|create(\s+company)?|add(\s+company)?/i }),
    page.getByRole('button', { name: /new\s+company|create(\s+company)?|add(\s+company)?/i }),
  ]);

  if (!createAction) {
    await page.reload({ waitUntil: 'domcontentloaded' });
    createAction = await firstVisible([
      page.locator('a[href$="/admin/companies/create"]'),
      page.getByRole('link', { name: /new\s+company|create(\s+company)?|add(\s+company)?/i }),
      page.getByRole('button', { name: /new\s+company|create(\s+company)?|add(\s+company)?/i }),
    ]);
  }

  if (!createAction) {
    throw new Error('Create/New company action not found on companies list page');
  }

  await expect(createAction).toBeVisible();
  await createAction.click();

  await expect(page.getByRole('button', { name: /create|save/i }).first()).toBeVisible();
}

async function fillBestEffortCompanyForm(page, companyName, uniqueSuffix) {
  const nameField = await firstVisible([
    page.getByRole('textbox', { name: /^name\*?$/i }),
    page.getByRole('textbox', { name: /company\s*name\*?/i }),
  ]);

  if (nameField) {
    await nameField.fill(companyName);
  }

  const codeField = await firstVisible([
    page.getByRole('textbox', { name: /^code\*?$/i }),
    page.getByLabel(/^code\*?$/i),
  ]);

  if (codeField) {
    await codeField.fill(`PW${uniqueSuffix}`);
  }

  const emailCandidates = page.getByRole('textbox', { name: /email/i });
  if (await emailCandidates.count()) {
    await emailCandidates.first().fill(`qa+${uniqueSuffix}@example.com`);
  }

  const phoneCandidates = page.getByRole('textbox', { name: /phone|mobile|contact/i });
  if (await phoneCandidates.count()) {
    await phoneCandidates.first().fill('9876543210');
  }

  const websiteCandidates = page.getByRole('textbox', { name: /website|url/i });
  if (await websiteCandidates.count()) {
    await websiteCandidates.first().fill(`https://example-${uniqueSuffix}.com`);
  }

  const textareas = page.locator('textarea:visible');
  if (await textareas.count()) {
    await textareas.first().fill(`Automated CRUD test ${uniqueSuffix}`);
  }
}

async function submitPrimaryForm(page, actionNamePattern) {
  const primaryAction = page.getByRole('button', { name: actionNamePattern }).first();
  await expect(primaryAction).toBeVisible();
  await primaryAction.click();
}

async function searchCompany(page, name) {
  const searchInput = await firstVisible([
    page.getByRole('searchbox', { name: /search/i }),
    page.getByRole('textbox', { name: /search/i }),
    page.locator('input[type="search"]'),
  ]);

  if (searchInput) {
    await searchInput.fill(name);
  }
}

async function ensureOnCompaniesList(page) {
  await page.waitForURL(/\/admin\/companies(\/create|\/\d+\/edit)?$/, { timeout: 15000 });

  if (!/\/admin\/companies$/.test(page.url())) {
    try {
      await page.goto(companiesUrl, { waitUntil: 'domcontentloaded' });
    } catch (error) {
      // Filament/Livewire can interrupt a fast follow-up navigation during redirect; retry once.
      const message = String(error);
      if (!message.includes('net::ERR_ABORTED')) {
        throw error;
      }

      await page.waitForLoadState('domcontentloaded');
      await page.goto(companiesUrl, { waitUntil: 'domcontentloaded' });
    }
  }

  await expect(page).toHaveURL(/\/admin\/companies(?:\?.*)?$/);
}

test.describe.serial('Admin companies CRUD', () => {
  test('create, read, update, delete company record', async ({ page }) => {
    const seed = Date.now().toString().slice(-6);
    const createdName = `PW Company ${seed}`;
    const updatedCode = `PW${seed}U`;

    await loginIfNeeded(page);

    // Create
    await openCreateForm(page);
    await fillBestEffortCompanyForm(page, createdName, seed);
    await submitPrimaryForm(page, /create|save/i);
    await ensureOnCompaniesList(page);

    // Read
    await searchCompany(page, createdName);
    await expect(page.getByRole('row', { name: new RegExp(createdName, 'i') })).toBeVisible();

    // Update
    const createdRow = page.getByRole('row', { name: new RegExp(createdName, 'i') }).first();
    const editButton = createdRow.getByRole('link', { name: /edit/i }).first();
    await expect(editButton).toBeVisible();
    await editButton.click();

    if (await companyNameInput(page).isEditable()) {
      await companyNameInput(page).fill(`${createdName} Updated`);
    }
    await companyCodeInput(page).fill(updatedCode);
    await submitPrimaryForm(page, /save|update/i);
    await page.waitForURL(/\/admin\/companies\/\d+\/edit$/, { timeout: 15000 });
    await expect(companyCodeInput(page)).toHaveValue(updatedCode);

    // Delete (from edit page header action)

    const deleteButton = page.getByRole('button', { name: /delete/i }).first();
    await expect(deleteButton).toBeVisible();
    await deleteButton.click();

    const confirmDelete = page.getByRole('button', { name: /^delete$/i }).first();
    await expect(confirmDelete).toBeVisible();
    await confirmDelete.click();

    await expect(page).toHaveURL(/\/admin\/companies/);
    await searchCompany(page, updatedCode);
    await expect(page.getByRole('row', { name: new RegExp(updatedCode, 'i') })).toHaveCount(0);
  });
});
