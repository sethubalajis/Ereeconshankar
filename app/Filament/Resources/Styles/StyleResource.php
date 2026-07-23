<?php

namespace App\Filament\Resources\Styles;

use App\Filament\Resources\Styles\Pages\CreateStyle;
use App\Filament\Resources\Styles\Pages\EditStyle;
use App\Filament\Resources\Styles\Pages\ListStyles;
use App\Filament\Resources\Styles\Schemas\StyleForm;
use App\Filament\Resources\Styles\Tables\StylesTable;
use App\Models\Style;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StyleResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '4. Merchandise Management';
    protected static ?string $model = Style::class;
    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function form(Schema $schema): Schema
    {
        return StyleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StylesTable::configure($table);
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
            'index' => ListStyles::route('/'),
            'create' => CreateStyle::route('/create'),
            'edit' => EditStyle::route('/{record}/edit'),
        ];
    }
}
