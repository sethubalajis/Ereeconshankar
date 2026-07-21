<?php

use App\Filament\Resources\BranchSettings\Pages\CreateBranchSetting;
use App\Filament\Resources\BranchSettings\Pages\EditBranchSetting;
use App\Filament\Resources\BranchSettings\Pages\ListBranchSettings;
use App\Models\Branch;
use App\Models\BranchSetting;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBranchSettingsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createBranchSettingTestBranch(): Branch
{
    $company = Company::query()->create(['name' => 'Branch Setting Co', 'code' => 'BSC001', 'status' => true]);
    return Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Branch Setting Branch', 'code' => 'BSB001', 'status' => true]);
}

it('redirects guests away from branch settings list', function (): void {
    get('/admin/branch-settings')->assertRedirect('/admin/login');
});

it('redirects guests away from create branch setting page', function (): void {
    get('/admin/branch-settings/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a branch setting', function (): void {
    actingAs(makeBranchSettingsAdminUser());

    Livewire::test(CreateBranchSetting::class)
        ->fillForm(['branch_id' => null, 'setting_key' => '', 'setting_value' => ''])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'setting_key' => 'required']);
});

it('performs branch setting crud operations', function (): void {
    actingAs(makeBranchSettingsAdminUser());

    $branch = createBranchSettingTestBranch();

    Livewire::test(CreateBranchSetting::class)
        ->fillForm(['branch_id' => $branch->getKey(), 'setting_key' => 'timezone', 'setting_value' => 'Asia/Kolkata'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = BranchSetting::query()->where('setting_key', 'timezone')->firstOrFail();

    Livewire::test(ListBranchSettings::class)->assertSuccessful()->assertSee('timezone');

    Livewire::test(EditBranchSetting::class, ['record' => $record->getKey()])
        ->fillForm(['branch_id' => $branch->getKey(), 'setting_key' => 'currency', 'setting_value' => 'INR'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->setting_key)->toBe('currency');
    expect($record->setting_value)->toBe('INR');

    Livewire::test(EditBranchSetting::class, ['record' => $record->getKey()])->callAction('delete');
    expect(BranchSetting::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
