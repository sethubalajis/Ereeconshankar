<?php

namespace App\Filament\Resources\ComboOffers;

use App\Filament\Resources\ComboOffers\Pages\CreateComboOffer;
use App\Filament\Resources\ComboOffers\Pages\EditComboOffer;
use App\Filament\Resources\ComboOffers\Pages\ListComboOffers;
use App\Filament\Resources\ComboOffers\Schemas\ComboOfferForm;
use App\Filament\Resources\ComboOffers\Tables\ComboOffersTable;
use App\Models\ComboOffer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ComboOfferResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '13. Promotions & Discounts';
    protected static ?int $navigationSort = 7;
    protected static ?string $model = ComboOffer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    public static function form(Schema $schema): Schema
    {
        return ComboOfferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComboOffersTable::configure($table);
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
            'index' => ListComboOffers::route('/'),
            'create' => CreateComboOffer::route('/create'),
            'edit' => EditComboOffer::route('/{record}/edit'),
        ];
    }
}
