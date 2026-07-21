<?php

use App\Filament\Resources\GstCategories\Pages\CreateGstCategory;
use App\Filament\Resources\GstCategories\Pages\EditGstCategory;
use App\Filament\Resources\GstCategories\Pages\ListGstCategories;
use App\Models\GstCategory;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeGstCategoriesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from gst categories list', function (): void { get('/admin/gst-categories')->assertRedirect('/admin/login'); });
it('redirects guests away from create gst category page', function (): void { get('/admin/gst-categories/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a gst category', function (): void {
    actingAs(makeGstCategoriesAdminUser());
    Livewire::test(CreateGstCategory::class)
        ->fillForm(['name' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'status' => 'required']);
});

it('performs gst category crud operations', function (): void {
    actingAs(makeGstCategoriesAdminUser());

    Livewire::test(CreateGstCategory::class)
        ->fillForm(['name' => 'GST Standard', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = GstCategory::query()->firstOrFail();
    Livewire::test(ListGstCategories::class)->assertSuccessful()->assertSee('GST Standard');

    Livewire::test(EditGstCategory::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'GST Updated', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(EditGstCategory::class, ['record' => $record->getKey()])->callAction('delete');
});
