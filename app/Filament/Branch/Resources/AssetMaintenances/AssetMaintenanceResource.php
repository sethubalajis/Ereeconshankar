<?php

namespace App\Filament\Branch\Resources\AssetMaintenances;

use App\Filament\Branch\Resources\AssetMaintenances\Pages\CreateAssetMaintenance;
use App\Filament\Branch\Resources\AssetMaintenances\Pages\EditAssetMaintenance;
use App\Filament\Branch\Resources\AssetMaintenances\Pages\ListAssetMaintenances;
use App\Filament\Branch\Resources\AssetMaintenances\Schemas\AssetMaintenanceForm;
use App\Filament\Branch\Resources\AssetMaintenances\Tables\AssetMaintenancesTable;
use App\Models\AssetMaintenance;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssetMaintenanceResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '18. Asset Management';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = AssetMaintenance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    public static function form(Schema $schema): Schema
    {
        return AssetMaintenanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetMaintenancesTable::configure($table);
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
            'index' => ListAssetMaintenances::route('/'),
            'create' => CreateAssetMaintenance::route('/create'),
            'edit' => EditAssetMaintenance::route('/{record}/edit'),
        ];
    }
}
