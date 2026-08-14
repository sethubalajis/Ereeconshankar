<?php

namespace App\Filament\Branch\Resources\InventoryReconciliations;

use App\Filament\Branch\Resources\InventoryReconciliations\Pages\CreateInventoryReconciliation;
use App\Filament\Branch\Resources\InventoryReconciliations\Pages\EditInventoryReconciliation;
use App\Filament\Branch\Resources\InventoryReconciliations\Pages\ListInventoryReconciliations;
use App\Filament\Branch\Resources\InventoryReconciliations\Schemas\InventoryReconciliationForm;
use App\Filament\Branch\Resources\InventoryReconciliations\Tables\InventoryReconciliationsTable;
use App\Models\InventoryReconciliation;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventoryReconciliationResource extends BaseBranchResource
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
