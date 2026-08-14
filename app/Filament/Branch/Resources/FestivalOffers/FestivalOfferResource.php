<?php

namespace App\Filament\Branch\Resources\FestivalOffers;

use App\Filament\Branch\Resources\FestivalOffers\Pages\CreateFestivalOffer;
use App\Filament\Branch\Resources\FestivalOffers\Pages\EditFestivalOffer;
use App\Filament\Branch\Resources\FestivalOffers\Pages\ListFestivalOffers;
use App\Filament\Branch\Resources\FestivalOffers\Schemas\FestivalOfferForm;
use App\Filament\Branch\Resources\FestivalOffers\Tables\FestivalOffersTable;
use App\Models\FestivalOffer;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FestivalOfferResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '13. Promotions & Discounts';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = FestivalOffer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    public static function form(Schema $schema): Schema
    {
        return FestivalOfferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FestivalOffersTable::configure($table);
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
            'index' => ListFestivalOffers::route('/'),
            'create' => CreateFestivalOffer::route('/create'),
            'edit' => EditFestivalOffer::route('/{record}/edit'),
        ];
    }
}
