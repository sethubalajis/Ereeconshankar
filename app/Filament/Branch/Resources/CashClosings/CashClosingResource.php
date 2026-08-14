<?php

namespace App\Filament\Branch\Resources\CashClosings;

use App\Filament\Branch\Resources\CashClosings\Pages\CreateCashClosing;
use App\Filament\Branch\Resources\CashClosings\Pages\EditCashClosing;
use App\Filament\Branch\Resources\CashClosings\Pages\ListCashClosings;
use App\Filament\Branch\Resources\CashClosings\Schemas\CashClosingForm;
use App\Filament\Branch\Resources\CashClosings\Tables\CashClosingsTable;
use App\Models\CashClosing;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CashClosingResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '11. Retail POS';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = CashClosing::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return CashClosingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CashClosingsTable::configure($table);
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
            'index' => ListCashClosings::route('/'),
            'create' => CreateCashClosing::route('/create'),
            'edit' => EditCashClosing::route('/{record}/edit'),
        ];
    }
}
