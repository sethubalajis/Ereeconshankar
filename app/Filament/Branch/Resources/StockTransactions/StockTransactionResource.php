<?php

namespace App\Filament\Branch\Resources\StockTransactions;

use App\Filament\Branch\Resources\StockTransactions\Pages\CreateStockTransaction;
use App\Filament\Branch\Resources\StockTransactions\Pages\EditStockTransaction;
use App\Filament\Branch\Resources\StockTransactions\Pages\ListStockTransactions;
use App\Filament\Branch\Resources\StockTransactions\Schemas\StockTransactionForm;
use App\Filament\Branch\Resources\StockTransactions\Tables\StockTransactionsTable;
use App\Models\StockTransaction;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockTransactionResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '7. Inventory Management';
    protected static ?string $model = StockTransaction::class;
    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    // Removed canCreate and canEdit to allow these actions

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
            'create' => CreateStockTransaction::route('/create'),
            'edit' => EditStockTransaction::route('/{record}/edit'),
        ];
    }
}
