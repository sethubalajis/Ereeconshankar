<?php

namespace App\Filament\Resources\InventoryReconciliations;

use App\Filament\Resources\InventoryReconciliations\Pages\CreateInventoryReconciliation;
use App\Filament\Resources\InventoryReconciliations\Pages\EditInventoryReconciliation;
use App\Filament\Resources\InventoryReconciliations\Pages\ListInventoryReconciliations;
use App\Filament\Resources\InventoryReconciliations\Schemas\InventoryReconciliationForm;
use App\Filament\Resources\InventoryReconciliations\Tables\InventoryReconciliationsTable;
use App\Models\InventoryReconciliation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventoryReconciliationResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '7. Inventory Management';
    protected static ?string $model = InventoryReconciliation::class;
    protected static ?int $navigationSort = 8;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    public static function form(Schema $schema): Schema
    {
        return InventoryReconciliationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoryReconciliationsTable::configure($table);
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
            'index' => ListInventoryReconciliations::route('/'),
            'create' => CreateInventoryReconciliation::route('/create'),
            'edit' => EditInventoryReconciliation::route('/{record}/edit'),
        ];
    }
}
