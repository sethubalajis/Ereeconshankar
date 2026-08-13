<?php

namespace App\Filament\Branch\Resources\ComboOffers;

use App\Filament\Branch\Resources\ComboOffers\Pages\CreateComboOffer;
use App\Filament\Branch\Resources\ComboOffers\Pages\EditComboOffer;
use App\Filament\Branch\Resources\ComboOffers\Pages\ListComboOffers;
use App\Filament\Branch\Resources\ComboOffers\Schemas\ComboOfferForm;
use App\Filament\Branch\Resources\ComboOffers\Tables\ComboOffersTable;
use App\Models\ComboOffer;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ComboOfferResource extends BaseBranchResource
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
