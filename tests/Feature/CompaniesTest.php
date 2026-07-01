<?php

use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Models\Company;
use App\Models\User;
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
