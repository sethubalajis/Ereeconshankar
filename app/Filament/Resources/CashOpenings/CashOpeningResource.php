<?php

namespace App\Filament\Resources\CashOpenings;

use App\Filament\Resources\CashOpenings\Pages\CreateCashOpening;
use App\Filament\Resources\CashOpenings\Pages\EditCashOpening;
use App\Filament\Resources\CashOpenings\Pages\ListCashOpenings;
use App\Filament\Resources\CashOpenings\Schemas\CashOpeningForm;
use App\Filament\Resources\CashOpenings\Tables\CashOpeningsTable;
use App\Models\CashOpening;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CashOpeningResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '11. Retail POS';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = CashOpening::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CashOpeningForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CashOpeningsTable::configure($table);
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
            'index' => ListCashOpenings::route('/'),
            'create' => CreateCashOpening::route('/create'),
            'edit' => EditCashOpening::route('/{record}/edit'),
        ];
    }
}
