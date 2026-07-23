<?php

namespace App\Filament\Resources\TrialBalances;

use App\Filament\Resources\TrialBalances\Pages\CreateTrialBalance;
use App\Filament\Resources\TrialBalances\Pages\EditTrialBalance;
use App\Filament\Resources\TrialBalances\Pages\ListTrialBalances;
use App\Filament\Resources\TrialBalances\Schemas\TrialBalanceForm;
use App\Filament\Resources\TrialBalances\Tables\TrialBalancesTable;
use App\Models\TrialBalance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TrialBalanceResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '14. Finance & Accounts';
    protected static ?int $navigationSort = 13;
    protected static ?string $model = TrialBalance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return TrialBalanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrialBalancesTable::configure($table);
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
            'index' => ListTrialBalances::route('/'),
            'create' => CreateTrialBalance::route('/create'),
            'edit' => EditTrialBalance::route('/{record}/edit'),
        ];
    }
}
