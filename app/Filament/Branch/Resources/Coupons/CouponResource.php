<?php

namespace App\Filament\Branch\Resources\Coupons;

use App\Filament\Branch\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Branch\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Branch\Resources\Coupons\Pages\ListCoupons;
use App\Filament\Branch\Resources\Coupons\Schemas\CouponForm;
use App\Filament\Branch\Resources\Coupons\Tables\CouponsTable;
use App\Models\Coupon;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CouponResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '13. Promotions & Discounts';
    protected static ?int $navigationSort = 3;
    protected static ?string $model = Coupon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    public static function form(Schema $schema): Schema
    {
        return CouponForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CouponsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit' => EditCoupon::route('/{record}/edit'),
        ];
    }
}
