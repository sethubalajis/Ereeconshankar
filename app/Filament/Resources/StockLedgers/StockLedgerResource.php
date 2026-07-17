<?php

namespace App\Filament\Resources\StockLedgers;

use App\Filament\Resources\StockLedgers\Pages\CreateStockLedger;
use App\Filament\Resources\StockLedgers\Pages\EditStockLedger;
use App\Filament\Resources\StockLedgers\Pages\ListStockLedgers;
use App\Filament\Resources\StockLedgers\Schemas\StockLedgerForm;
use App\Filament\Resources\StockLedgers\Tables\StockLedgersTable;
use App\Models\StockLedger;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StockLedgerResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '7. Inventory Management';
    protected static ?string $model = StockLedger::class;
    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // Removed canCreate and canEdit to allow these actions

    public static function form(Schema $schema): Schema
    {
        return StockLedgerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockLedgersTable::configure($table);
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
            'index' => ListStockLedgers::route('/'),
            'create' => CreateStockLedger::route('/create'),
            'edit' => EditStockLedger::route('/{record}/edit'),
        ];
    }
}
