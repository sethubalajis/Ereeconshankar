<?php

use App\Filament\Resources\ComplaintCategories\Pages\CreateComplaintCategory;
use App\Filament\Resources\ComplaintCategories\Pages\EditComplaintCategory;
use App\Filament\Resources\ComplaintCategories\Pages\ListComplaintCategories;
use App\Models\ComplaintCategory;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeComplaintCategoriesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from complaint categories list', function (): void { get('/admin/complaint-categories')->assertRedirect('/admin/login'); });
it('redirects guests away from create complaint category page', function (): void { get('/admin/complaint-categories/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a complaint category', function (): void {
    actingAs(makeComplaintCategoriesAdminUser());
    Livewire::test(CreateComplaintCategory::class)
        ->fillForm(['name' => '', 'code' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'code' => 'required', 'status' => 'required']);
});

it('performs complaint category crud operations', function (): void {
    actingAs(makeComplaintCategoriesAdminUser());

    Livewire::test(CreateComplaintCategory::class)
        ->fillForm(['name' => 'Delivery Issue', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ComplaintCategory::query()->firstOrFail();
    Livewire::test(ListComplaintCategories::class)->assertSuccessful()->assertSee('Delivery Issue');

    Livewire::test(EditComplaintCategory::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Refund Issue', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Refund Issue');

    Livewire::test(EditComplaintCategory::class, ['record' => $record->getKey()])->callAction('delete');
    expect(ComplaintCategory::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
