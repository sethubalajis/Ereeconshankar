<?php

namespace App\Filament\Resources\TransitInventories;

use App\Filament\Resources\TransitInventories\Pages\CreateTransitInventory;
use App\Filament\Resources\TransitInventories\Pages\EditTransitInventory;
use App\Filament\Resources\TransitInventories\Pages\ListTransitInventories;
use App\Filament\Resources\TransitInventories\Schemas\TransitInventoryForm;
use App\Filament\Resources\TransitInventories\Tables\TransitInventoriesTable;
use App\Models\TransitInventory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransitInventoryResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '10. Stock Transfer';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = TransitInventory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TransitInventoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransitInventoriesTable::configure($table);
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
            'index' => ListTransitInventories::route('/'),
            'create' => CreateTransitInventory::route('/create'),
            'edit' => EditTransitInventory::route('/{record}/edit'),
        ];
    }
}
