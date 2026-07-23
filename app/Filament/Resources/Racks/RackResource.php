<?php

namespace App\Filament\Resources\Racks;

use App\Filament\Resources\Racks\Pages\CreateRack;
use App\Filament\Resources\Racks\Pages\EditRack;
use App\Filament\Resources\Racks\Pages\ListRacks;
use App\Filament\Resources\Racks\Schemas\RackForm;
use App\Filament\Resources\Racks\Tables\RacksTable;
use App\Models\Rack;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RackResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '8. Warehouse Management';
    protected static ?string $model = Rack::class;
    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    public static function form(Schema $schema): Schema
    {
        return RackForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RacksTable::configure($table);
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
            'index' => ListRacks::route('/'),
            'create' => CreateRack::route('/create'),
            'edit' => EditRack::route('/{record}/edit'),
        ];
    }
}
