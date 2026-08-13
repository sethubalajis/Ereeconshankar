<?php

use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Models\Brand;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBrandValidationAdminUser(): User
{
    $role = Role::query()->firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('prevents duplicate brand name at validation level', function (): void {
    actingAs(makeBrandValidationAdminUser());

    Brand::query()->create([
        'name' => 'Duplicate Brand',
        'code' => 'BRV001',
        'status' => true,
    ]);

    Livewire::test(CreateBrand::class)
        ->fillForm([
            'name' => 'Duplicate Brand',
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'unique',
        ]);
});

it('prevents duplicate brand code at validation level', function (): void {
    // Code is auto-generated; duplicate prevention is at DB level, not form level.
})->skip('Code is auto-generated and not submitted via form — no form validation to test.');


it('uploads and stores brand logo image', function (): void {
    $disk = 'public';
    Storage::fake($disk);
    actingAs(makeBrandValidationAdminUser());

    $logo = UploadedFile::fake()->image('brand-logo.png');

    Livewire::test(CreateBrand::class)
        ->fillForm([
            'name' => 'Brand Logo Upload',
            'logo' => $logo,
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $brand = Brand::query()->where('name', 'Brand Logo Upload')->first();

    expect($brand)->not->toBeNull();
    expect($brand->logo)->not->toBeEmpty();
    Storage::disk($disk)->assertExists($brand->logo);
});
