<?php

namespace App\Filament\Branch\Resources\PromoCodes;

use App\Filament\Branch\Resources\PromoCodes\Pages\CreatePromoCode;
use App\Filament\Branch\Resources\PromoCodes\Pages\EditPromoCode;
use App\Filament\Branch\Resources\PromoCodes\Pages\ListPromoCodes;
use App\Filament\Branch\Resources\PromoCodes\Schemas\PromoCodeForm;
use App\Filament\Branch\Resources\PromoCodes\Tables\PromoCodesTable;
use App\Models\PromoCode;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PromoCodeResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '13. Promotions & Discounts';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = PromoCode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    public static function form(Schema $schema): Schema
    {
        return PromoCodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PromoCodesTable::configure($table);
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
            'index' => ListPromoCodes::route('/'),
            'create' => CreatePromoCode::route('/create'),
            'edit' => EditPromoCode::route('/{record}/edit'),
        ];
    }
}
