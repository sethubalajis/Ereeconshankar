<?php

namespace App\Filament\Branch\Resources\PriceLists;

use App\Filament\Branch\Resources\PriceLists\Pages\CreatePriceList;
use App\Filament\Branch\Resources\PriceLists\Pages\EditPriceList;
use App\Filament\Branch\Resources\PriceLists\Pages\ListPriceLists;
use App\Filament\Branch\Resources\PriceLists\Schemas\PriceListForm;
use App\Filament\Branch\Resources\PriceLists\Tables\PriceListsTable;
use App\Models\PriceList;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PriceListResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '13. Promotions & Discounts';
    protected static ?int $navigationSort = 8;
    protected static ?string $model = PriceList::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function form(Schema $schema): Schema
    {
        return PriceListForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PriceListsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\PriceLists\RelationManagers\PriceListItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPriceLists::route('/'),
            'create' => CreatePriceList::route('/create'),
            'edit' => EditPriceList::route('/{record}/edit'),
        ];
    }
}
