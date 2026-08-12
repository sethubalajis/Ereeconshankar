<?php

namespace App\Filament\Branch\Resources\InventoryCounts;

use App\Filament\Branch\Resources\InventoryCounts\Pages\CreateInventoryCount;
use App\Filament\Branch\Resources\InventoryCounts\Pages\EditInventoryCount;
use App\Filament\Branch\Resources\InventoryCounts\Pages\ListInventoryCounts;
use App\Filament\Branch\Resources\InventoryCounts\Schemas\InventoryCountForm;
use App\Filament\Branch\Resources\InventoryCounts\Tables\InventoryCountsTable;
use App\Models\InventoryCount;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventoryCountResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '7. Inventory Management';
    protected static ?string $model = InventoryCount::class;
    protected static ?int $navigationSort = 7;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    public static function form(Schema $schema): Schema
    {
        return InventoryCountForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoryCountsTable::configure($table);
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
            'index' => ListInventoryCounts::route('/'),
            'create' => CreateInventoryCount::route('/create'),
            'edit' => EditInventoryCount::route('/{record}/edit'),
        ];
    }
}
