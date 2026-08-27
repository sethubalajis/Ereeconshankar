<?php

namespace App\Filament\Resources\StockTransactions;

use App\Filament\Resources\StockTransactions\Pages\CreateStockTransaction;
use App\Filament\Resources\StockTransactions\Pages\EditStockTransaction;
use App\Filament\Resources\StockTransactions\Pages\ListStockTransactions;
use App\Filament\Resources\StockTransactions\Schemas\StockTransactionForm;
use App\Filament\Resources\StockTransactions\Tables\StockTransactionsTable;
use App\Models\StockTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockTransactionResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '7. Inventory Management';
    protected static ?string $model = StockTransaction::class;
    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return StockTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockTransactionsTable::configure($table);
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
            'index' => ListStockTransactions::route('/'),
        ];
    }
}
