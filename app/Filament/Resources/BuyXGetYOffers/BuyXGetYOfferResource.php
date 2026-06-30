<?php

namespace App\Filament\Resources\BuyXGetYOffers;

use App\Filament\Resources\BuyXGetYOffers\Pages\CreateBuyXGetYOffer;
use App\Filament\Resources\BuyXGetYOffers\Pages\EditBuyXGetYOffer;
use App\Filament\Resources\BuyXGetYOffers\Pages\ListBuyXGetYOffers;
use App\Filament\Resources\BuyXGetYOffers\Schemas\BuyXGetYOfferForm;
use App\Filament\Resources\BuyXGetYOffers\Tables\BuyXGetYOffersTable;
use App\Models\BuyXGetYOffer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BuyXGetYOfferResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '13. Promotions & Discounts';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = BuyXGetYOffer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BuyXGetYOfferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BuyXGetYOffersTable::configure($table);
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
            'index' => ListBuyXGetYOffers::route('/'),
            'create' => CreateBuyXGetYOffer::route('/create'),
            'edit' => EditBuyXGetYOffer::route('/{record}/edit'),
        ];
    }
}
