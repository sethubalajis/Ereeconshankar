<?php

namespace App\Filament\Branch\Resources\CashOpenings;

use App\Filament\Branch\Resources\CashOpenings\Pages\CreateCashOpening;
use App\Filament\Branch\Resources\CashOpenings\Pages\EditCashOpening;
use App\Filament\Branch\Resources\CashOpenings\Pages\ListCashOpenings;
use App\Filament\Branch\Resources\CashOpenings\Schemas\CashOpeningForm;
use App\Filament\Branch\Resources\CashOpenings\Tables\CashOpeningsTable;
use App\Models\CashOpening;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CashOpeningResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '11. Retail POS';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = CashOpening::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

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
