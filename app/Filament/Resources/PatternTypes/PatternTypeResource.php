<?php

namespace App\Filament\Resources\PatternTypes;

use App\Filament\Resources\PatternTypes\Pages\CreatePatternType;
use App\Filament\Resources\PatternTypes\Pages\EditPatternType;
use App\Filament\Resources\PatternTypes\Pages\ListPatternTypes;
use App\Filament\Resources\PatternTypes\Schemas\PatternTypeForm;
use App\Filament\Resources\PatternTypes\Tables\PatternTypesTable;
use App\Models\PatternType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatternTypeResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '4. Merchandise Management';
    protected static ?string $model = PatternType::class;
    protected static ?int $navigationSort = 9;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PatternTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatternTypesTable::configure($table);
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
            'index' => ListPatternTypes::route('/'),
            'create' => CreatePatternType::route('/create'),
            'edit' => EditPatternType::route('/{record}/edit'),
        ];
    }
}
