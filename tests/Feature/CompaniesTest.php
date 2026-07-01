<?php

use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('redirects guests away from companies list', function (): void {
    get('/admin/companies')->assertRedirect('/admin/login');
});

it('allows authenticated users to access companies list', function (): void {
    actingAs(User::factory()->create());

    get('/admin/companies')->assertOk();
});

it('validates required fields when creating a company', function (): void {
    actingAs(User::factory()->create());

    Livewire::test(CreateCompany::class)
        ->fillForm([
            'name' => '',
            'code' => '',
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'code' => 'required',
        ]);
});

it('prevents duplicate company code at validation level', function (): void {
    actingAs(User::factory()->create());

    $existing = Company::factory()->create([
        'code' => 'DUP001',
    ]);

    Livewire::test(CreateCompany::class)
        ->fillForm([
            'name' => fake()->company(),
            'code' => $existing->code,
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'code' => 'unique',
        ]);
});

it('prevents duplicate company name', function (): void {
    actingAs(User::factory()->create());

    $existing = Company::factory()->create([
        'name' => 'Duplicate Co',
        'code' => 'DUP100',
    ]);

    Livewire::test(CreateCompany::class)
        ->fillForm([
            'name' => $existing->name,
            'code' => 'DUP101',
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'unique',
        ]);
});

it('blocks unauthenticated users from create company page', function (): void {
    get('/admin/companies/create')->assertRedirect('/admin/login');
});

it('uploads and stores company logo image', function (): void {
    Storage::fake('public');
    actingAs(User::factory()->create());

    $logo = UploadedFile::fake()->image('company-logo.png');

    Livewire::test(CreateCompany::class)
        ->fillForm([
            'name' => 'Logo Upload Co',
            'code' => 'LOGO001',
            // Expected final behavior after switching CompanyForm::logo to FileUpload.
            'logo' => $logo,
            'status' => true,
        ])
        ->call('create');

    $company = Company::query()->where('code', 'LOGO001')->first();

    expect($company)->not->toBeNull();
    expect($company->logo)->not->toBeEmpty();
    Storage::disk('public')->assertExists($company->logo);
})->skip('CompanyForm::logo is currently TextInput. Switch to FileUpload to enable this upload test.');
