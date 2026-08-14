<?php

namespace App\Filament\Branch\Resources\TransitInventories;

use App\Filament\Branch\Resources\TransitInventories\Pages\CreateTransitInventory;
use App\Filament\Branch\Resources\TransitInventories\Pages\EditTransitInventory;
use App\Filament\Branch\Resources\TransitInventories\Pages\ListTransitInventories;
use App\Filament\Branch\Resources\TransitInventories\Schemas\TransitInventoryForm;
use App\Filament\Branch\Resources\TransitInventories\Tables\TransitInventoriesTable;
use App\Models\TransitInventory;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransitInventoryResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '10. Stock Transfer';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = TransitInventory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

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
