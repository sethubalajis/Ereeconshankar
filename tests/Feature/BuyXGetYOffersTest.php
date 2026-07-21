<?php

use App\Filament\Resources\BuyXGetYOffers\Pages\CreateBuyXGetYOffer;
use App\Filament\Resources\BuyXGetYOffers\Pages\EditBuyXGetYOffer;
use App\Filament\Resources\BuyXGetYOffers\Pages\ListBuyXGetYOffers;
use App\Models\BuyXGetYOffer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBuyXGetYOffersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createBuyXGetYOfferProducts(): array
{
    $category = ProductCategory::query()->create(['name' => 'Offer Products', 'code' => 'OFR001', 'status' => true]);
    $buyProduct = Product::query()->create(['product_category_id' => $category->getKey(), 'name' => 'Buy Product', 'code' => 'BUY001', 'is_active' => true]);
    $getProduct = Product::query()->create(['product_category_id' => $category->getKey(), 'name' => 'Get Product', 'code' => 'GET001', 'is_active' => true]);

    return compact('buyProduct', 'getProduct');
}

it('redirects guests away from buy x get y offers list', function (): void { get('/admin/buy-x-get-y-offers')->assertRedirect('/admin/login'); });
it('redirects guests away from create buy x get y offer page', function (): void { get('/admin/buy-x-get-y-offers/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a buy x get y offer', function (): void {
    actingAs(makeBuyXGetYOffersAdminUser());
    Livewire::test(CreateBuyXGetYOffer::class)
        ->fillForm(['buy_product_id' => null, 'get_product_id' => null, 'buy_quantity' => null, 'get_quantity' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['buy_product_id' => 'required', 'get_product_id' => 'required', 'buy_quantity' => 'required', 'get_quantity' => 'required', 'status' => 'required']);
});

it('performs buy x get y offer crud operations', function (): void {
    actingAs(makeBuyXGetYOffersAdminUser());
    ['buyProduct' => $buyProduct, 'getProduct' => $getProduct] = createBuyXGetYOfferProducts();

    Livewire::test(CreateBuyXGetYOffer::class)
        ->fillForm(['buy_product_id' => $buyProduct->getKey(), 'get_product_id' => $getProduct->getKey(), 'buy_quantity' => 2, 'get_quantity' => 1, 'start_date' => '2026-07-21', 'end_date' => '2026-08-21', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = BuyXGetYOffer::query()->firstOrFail();
    Livewire::test(ListBuyXGetYOffers::class)->assertSuccessful()->assertSee('Buy Product');

    Livewire::test(EditBuyXGetYOffer::class, ['record' => $record->getKey()])
        ->fillForm(['buy_product_id' => $buyProduct->getKey(), 'get_product_id' => $getProduct->getKey(), 'buy_quantity' => 3, 'get_quantity' => 2, 'start_date' => '2026-07-22', 'end_date' => '2026-08-22', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((int) $record->buy_quantity)->toBe(3);

    Livewire::test(EditBuyXGetYOffer::class, ['record' => $record->getKey()])->callAction('delete');
    expect(BuyXGetYOffer::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
