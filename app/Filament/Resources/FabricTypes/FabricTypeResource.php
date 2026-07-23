<?php

namespace App\Filament\Resources\FabricTypes;

use App\Filament\Resources\FabricTypes\Pages\CreateFabricType;
use App\Filament\Resources\FabricTypes\Pages\EditFabricType;
use App\Filament\Resources\FabricTypes\Pages\ListFabricTypes;
use App\Filament\Resources\FabricTypes\Schemas\FabricTypeForm;
use App\Filament\Resources\FabricTypes\Tables\FabricTypesTable;
use App\Models\FabricType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FabricTypeResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '4. Merchandise Management';
    protected static ?string $model = FabricType::class;
    protected static ?int $navigationSort = 8;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function form(Schema $schema): Schema
    {
        return FabricTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FabricTypesTable::configure($table);
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
            'index' => ListFabricTypes::route('/'),
            'create' => CreateFabricType::route('/create'),
            'edit' => EditFabricType::route('/{record}/edit'),
        ];
    }
}
