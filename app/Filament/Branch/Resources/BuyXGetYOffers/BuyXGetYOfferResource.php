<?php

namespace App\Filament\Branch\Resources\BuyXGetYOffers;

use App\Filament\Branch\Resources\BuyXGetYOffers\Pages\CreateBuyXGetYOffer;
use App\Filament\Branch\Resources\BuyXGetYOffers\Pages\EditBuyXGetYOffer;
use App\Filament\Branch\Resources\BuyXGetYOffers\Pages\ListBuyXGetYOffers;
use App\Filament\Branch\Resources\BuyXGetYOffers\Schemas\BuyXGetYOfferForm;
use App\Filament\Branch\Resources\BuyXGetYOffers\Tables\BuyXGetYOffersTable;
use App\Models\BuyXGetYOffer;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BuyXGetYOfferResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '13. Promotions & Discounts';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = BuyXGetYOffer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

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
