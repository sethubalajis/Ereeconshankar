<?php

use App\Filament\Resources\DashboardWidgets\Pages\CreateDashboardWidget;
use App\Filament\Resources\DashboardWidgets\Pages\EditDashboardWidget;
use App\Filament\Resources\DashboardWidgets\Pages\ListDashboardWidgets;
use App\Models\DashboardWidget;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeDashboardWidgetsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from dashboard widgets list', function (): void { get('/admin/dashboard-widgets')->assertRedirect('/admin/login'); });
it('redirects guests away from create dashboard widget page', function (): void { get('/admin/dashboard-widgets/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a dashboard widget', function (): void {
    actingAs(makeDashboardWidgetsAdminUser());
    Livewire::test(CreateDashboardWidget::class)
        ->fillForm(['name' => '', 'type' => '', 'config' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'type' => 'required', 'status' => 'required']);
});

it('performs dashboard widget crud operations', function (): void {
    actingAs(makeDashboardWidgetsAdminUser());

    Livewire::test(CreateDashboardWidget::class)
        ->fillForm(['name' => 'Sales Overview', 'type' => 'metric', 'config' => '{"color":"blue"}', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = DashboardWidget::query()->firstOrFail();
    Livewire::test(ListDashboardWidgets::class)->assertSuccessful()->assertSee('Sales Overview');

    Livewire::test(EditDashboardWidget::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Revenue Chart', 'type' => 'chart', 'config' => '{"color":"green"}', 'status' => true])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Revenue Chart');

    Livewire::test(EditDashboardWidget::class, ['record' => $record->getKey()])->callAction('delete');
    expect(DashboardWidget::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
