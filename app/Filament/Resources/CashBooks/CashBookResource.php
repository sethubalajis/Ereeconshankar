<?php

namespace App\Filament\Resources\CashBooks;

use App\Filament\Resources\CashBooks\Pages\CreateCashBook;
use App\Filament\Resources\CashBooks\Pages\EditCashBook;
use App\Filament\Resources\CashBooks\Pages\ListCashBooks;
use App\Filament\Resources\CashBooks\Schemas\CashBookForm;
use App\Filament\Resources\CashBooks\Tables\CashBooksTable;
use App\Models\CashBook;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CashBookResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '14. Finance & Accounts';
    protected static ?int $navigationSort = 7;
    protected static ?string $model = CashBook::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return CashBookForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CashBooksTable::configure($table);
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
            'index' => ListCashBooks::route('/'),
            'create' => CreateCashBook::route('/create'),
            'edit' => EditCashBook::route('/{record}/edit'),
        ];
    }
}
