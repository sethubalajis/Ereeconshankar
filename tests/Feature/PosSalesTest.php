<?php

use App\Filament\Resources\PosSales\Pages\CreatePosSale;
use App\Filament\Resources\PosSales\Pages\EditPosSale;
use App\Filament\Resources\PosSales\Pages\ListPosSales;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\FestivalOffer;
use App\Models\PosSale;
use App\Models\PosTerminal;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use App\Services\PosPromotionService;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePosSalesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createPosSaleBranchAndTerminal(): array
{
    $company = Company::query()->create(['name' => 'POS Sale Co', 'code' => 'PSC01', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'POS Sale Branch', 'code' => 'PSB02', 'status' => true]);
    $terminal = PosTerminal::query()->create(['branch_id' => $branch->getKey(), 'terminal_name' => 'POS Main', 'terminal_code' => 'POSM1', 'status' => true]);
    return compact('branch', 'terminal');
}

it('redirects guests away from pos sales list', function (): void { get('/admin/pos-sales')->assertRedirect('/admin/login'); });
it('redirects guests away from create pos sale page', function (): void { get('/admin/pos-sales/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a pos sale', function (): void {
    actingAs(makePosSalesAdminUser());

    Livewire::test(CreatePosSale::class)
        ->fillForm(['branch_id' => null, 'pos_terminal_id' => null, 'sale_date' => null, 'total_amount' => null, 'tax_amount' => null, 'discount_amount' => null, 'net_amount' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'pos_terminal_id' => 'required', 'sale_date' => 'required', 'total_amount' => 'required', 'tax_amount' => 'required', 'discount_amount' => 'required', 'net_amount' => 'required', 'status' => 'required']);
});

it('performs pos sale crud operations', function (): void {
    actingAs(makePosSalesAdminUser());
    ['branch' => $branch, 'terminal' => $terminal] = createPosSaleBranchAndTerminal();

    Livewire::test(CreatePosSale::class)
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'pos_terminal_id' => $terminal->getKey(),
            'sale_date' => '2026-07-21',
            'total_amount' => 1200,
            'tax_amount' => 120,
            'discount_amount' => 20,
            'net_amount' => 1300,
            'status' => 'completed',
            'posSaleItems' => [],
            'payments' => [],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PosSale::query()->firstOrFail();
    Livewire::test(ListPosSales::class)->assertSuccessful()->assertSee($record->invoice_number);

    Livewire::test(EditPosSale::class, ['record' => $record->getKey()])
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'pos_terminal_id' => $terminal->getKey(),
            'sale_date' => '2026-07-22',
            'total_amount' => 1500,
            'tax_amount' => 150,
            'discount_amount' => 50,
            'net_amount' => 1600,
            'status' => 'pending',
            'posSaleItems' => [],
            'payments' => [],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('pending');

    Livewire::test(EditPosSale::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PosSale::query()->whereKey($record->getKey())->exists())->toBeFalse();
});

it('discovers eligible customer benefits and promotions via PosPromotionService', function (): void {
    $group = CustomerGroup::create(['name' => 'VIP Group', 'code' => 'VIP01', 'discount_percentage' => 10, 'status' => true]);
    $customer = Customer::create([
        'name' => 'Jane Doe',
        'phone' => '9876543210',
        'customer_group_id' => $group->id,
        'total_points' => 100,
        'wallet_balance' => 250,
    ]);

    $product = Product::create(['name' => 'Test T-Shirt', 'code' => 'PRD-TSHIRT', 'is_active' => true]);
    $variant = ProductVariant::create(['product_id' => $product->id, 'sku' => 'TSHIRT-RED-M', 'selling_price' => 1000]);

    $coupon = Coupon::create([
        'code' => 'WELCOME10',
        'discount_percentage' => 10,
        'min_purchase_amount' => 500,
        'status' => true,
    ]);

    $festival = FestivalOffer::create([
        'name' => 'Diwali Bonanza',
        'start_date' => now()->subDay()->toDateString(),
        'end_date' => now()->addMonth()->toDateString(),
        'discount_percentage' => 5,
        'status' => true,
    ]);

    $service = new PosPromotionService();
    $cartItems = [
        ['product_id' => $product->id, 'product_variant_id' => $variant->id, 'quantity' => 1, 'unit_price' => 1000],
    ];

    $offers = $service->getEligibleOffers($customer->id, $cartItems);

    expect($offers)->toHaveKey('customer_group');
    expect($offers)->toHaveKey('loyalty_points');
    expect($offers)->toHaveKey('wallet_balance');
    expect($offers)->toHaveKey("coupon_{$coupon->id}");
    expect($offers)->toHaveKey("festival_offer_{$festival->id}");
});

it('allows cashier to select optional offers and recalculates prices live in pos sale form', function (): void {
    actingAs(makePosSalesAdminUser());
    ['branch' => $branch, 'terminal' => $terminal] = createPosSaleBranchAndTerminal();

    $group = CustomerGroup::create(['name' => 'Gold Group', 'code' => 'GOLD01', 'discount_percentage' => 10, 'status' => true]);
    $customer = Customer::create([
        'name' => 'John Cashier',
        'phone' => '9123456789',
        'customer_group_id' => $group->id,
        'total_points' => 50,
        'wallet_balance' => 100,
    ]);

    $product = Product::create(['name' => 'Casual Shoes', 'code' => 'SHOE01', 'is_active' => true]);
    $variant = ProductVariant::create(['product_id' => $product->id, 'sku' => 'SHOE-BL-42', 'selling_price' => 2000]);

    $paymentMode = \App\Models\PaymentMode::create(['name' => 'Cash', 'code' => 'CASH01', 'is_active' => true]);

    Livewire::test(CreatePosSale::class)
        ->fillForm([
            'branch_id' => $branch->id,
            'pos_terminal_id' => $terminal->id,
            'customer_id' => $customer->id,
            'sale_date' => now()->toDateString(),
            'posSaleItems' => [
                [
                    'product_id' => $product->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => 1,
                    'unit_price' => 2000,
                    'discount' => 0,
                    'total_price' => 2000,
                ],
            ],
            'payments' => [
                [
                    'payment_mode_id' => $paymentMode->id,
                    'amount' => 1750,
                ],
            ],
            'status' => 'completed',
        ])
        ->set('data.selected_offers', ['customer_group', 'loyalty_points'])
        ->call('create')
        ->assertHasNoFormErrors();

    $sale = PosSale::first();
    expect($sale)->not()->toBeNull();
    expect((float) $sale->total_amount)->toBe(2000.0);
    expect((float) $sale->discount_amount)->toBe(200.0); // 10% customer group discount on 2000
    expect((float) $sale->redeem_points_amount)->toBe(50.0); // 50 points redeemed
    expect($sale->applied_offers)->not()->toBeEmpty();
});
