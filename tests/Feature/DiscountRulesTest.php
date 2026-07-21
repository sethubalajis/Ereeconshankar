<?php

use App\Filament\Resources\DiscountRules\Pages\CreateDiscountRule;
use App\Filament\Resources\DiscountRules\Pages\EditDiscountRule;
use App\Filament\Resources\DiscountRules\Pages\ListDiscountRules;
use App\Models\DiscountRule;
use App\Models\PromotionType;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeDiscountRulesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createDiscountRulesPromotionType(): PromotionType
{
    return PromotionType::query()->create(['name' => 'Seasonal']);
}

it('redirects guests away from discount rules list', function (): void { get('/admin/discount-rules')->assertRedirect('/admin/login'); });
it('redirects guests away from create discount rule page', function (): void { get('/admin/discount-rules/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a discount rule', function (): void {
    actingAs(makeDiscountRulesAdminUser());
    Livewire::test(CreateDiscountRule::class)
        ->fillForm(['promotion_type_id' => null, 'name' => '', 'code' => '', 'discount_percentage' => null, 'discount_amount' => null, 'start_date' => null, 'end_date' => null, 'min_purchase_amount' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['promotion_type_id' => 'required', 'name' => 'required', 'code' => 'required', 'min_purchase_amount' => 'required', 'status' => 'required']);
});

it('performs discount rule crud operations', function (): void {
    actingAs(makeDiscountRulesAdminUser());
    $promotionType = createDiscountRulesPromotionType();

    Livewire::test(CreateDiscountRule::class)
        ->fillForm(['promotion_type_id' => $promotionType->getKey(), 'name' => 'July Offer', 'discount_percentage' => 10, 'discount_amount' => null, 'start_date' => '2026-07-21', 'end_date' => '2026-08-21', 'min_purchase_amount' => 1000, 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = DiscountRule::query()->firstOrFail();
    Livewire::test(ListDiscountRules::class)->assertSuccessful()->assertSee('July Offer');

    Livewire::test(EditDiscountRule::class, ['record' => $record->getKey()])
        ->fillForm(['promotion_type_id' => $promotionType->getKey(), 'name' => 'August Offer', 'discount_percentage' => 15, 'discount_amount' => null, 'start_date' => '2026-08-01', 'end_date' => '2026-09-01', 'min_purchase_amount' => 1500, 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('August Offer');

    Livewire::test(EditDiscountRule::class, ['record' => $record->getKey()])->callAction('delete');
    expect(DiscountRule::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
