<?php

namespace App\Filament\Resources\WarehouseZones;

use App\Filament\Resources\WarehouseZones\Pages\CreateWarehouseZone;
use App\Filament\Resources\WarehouseZones\Pages\EditWarehouseZone;
use App\Filament\Resources\WarehouseZones\Pages\ListWarehouseZones;
use App\Filament\Resources\WarehouseZones\Schemas\WarehouseZoneForm;
use App\Filament\Resources\WarehouseZones\Tables\WarehouseZonesTable;
use App\Models\WarehouseZone;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WarehouseZoneResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '8. Warehouse Management';
    protected static ?string $model = WarehouseZone::class;
    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return WarehouseZoneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WarehouseZonesTable::configure($table);
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
            'index' => ListWarehouseZones::route('/'),
            'create' => CreateWarehouseZone::route('/create'),
            'edit' => EditWarehouseZone::route('/{record}/edit'),
        ];
    }
}
