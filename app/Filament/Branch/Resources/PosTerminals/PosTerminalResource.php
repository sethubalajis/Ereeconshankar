<?php

namespace App\Filament\Branch\Resources\PosTerminals;

use App\Filament\Branch\Resources\PosTerminals\Pages\CreatePosTerminal;
use App\Filament\Branch\Resources\PosTerminals\Pages\EditPosTerminal;
use App\Filament\Branch\Resources\PosTerminals\Pages\ListPosTerminals;
use App\Filament\Branch\Resources\PosTerminals\Schemas\PosTerminalForm;
use App\Filament\Branch\Resources\PosTerminals\Tables\PosTerminalsTable;
use App\Models\PosTerminal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PosTerminalResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '11. Retail POS';
    protected static ?int $navigationSort = 1;
    protected static ?string $model = PosTerminal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    public static function form(Schema $schema): Schema
    {
        return PosTerminalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PosTerminalsTable::configure($table);
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
            'index' => ListPosTerminals::route('/'),
            'create' => CreatePosTerminal::route('/create'),
            'edit' => EditPosTerminal::route('/{record}/edit'),
        ];
    }
}
