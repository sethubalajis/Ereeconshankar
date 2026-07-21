<?php

use App\Filament\Resources\PatternTypes\Pages\CreatePatternType;
use App\Filament\Resources\PatternTypes\Pages\EditPatternType;
use App\Filament\Resources\PatternTypes\Pages\ListPatternTypes;
use App\Models\PatternType;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePatternTypesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from pattern types list', function (): void { get('/admin/pattern-types')->assertRedirect('/admin/login'); });
it('redirects guests away from create pattern type page', function (): void { get('/admin/pattern-types/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a pattern type', function (): void {
    actingAs(makePatternTypesAdminUser());

    Livewire::test(CreatePatternType::class)
        ->fillForm(['name' => '', 'code' => '', 'description' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'code' => 'required', 'status' => 'required']);
});

it('performs pattern type crud operations', function (): void {
    actingAs(makePatternTypesAdminUser());

    Livewire::test(CreatePatternType::class)
        ->fillForm(['name' => 'Striped', 'code' => 'PT001', 'description' => 'Striped pattern', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PatternType::query()->firstOrFail();
    Livewire::test(ListPatternTypes::class)->assertSuccessful()->assertSee('Striped');

    Livewire::test(EditPatternType::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Checked', 'code' => 'PT002', 'description' => 'Checked pattern', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Checked');

    Livewire::test(EditPatternType::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PatternType::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
