<?php

namespace App\Filament\Branch\Resources\Stocks;

use App\Filament\Branch\Resources\Stocks\Pages\CreateStock;
use App\Filament\Branch\Resources\Stocks\Pages\EditStock;
use App\Filament\Branch\Resources\Stocks\Pages\ListStocks;
use App\Filament\Branch\Resources\Stocks\Schemas\StockForm;
use App\Filament\Branch\Resources\Stocks\Tables\StocksTable;
use App\Models\Stock;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '7. Inventory Management';
    protected static ?string $model = Stock::class;
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    // Removed canCreate and canEdit to allow these actions

    public static function form(Schema $schema): Schema
    {
        return StockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StocksTable::configure($table);
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
            'index' => ListStocks::route('/'),
            'create' => CreateStock::route('/create'),
            'edit' => EditStock::route('/{record}/edit'),
        ];
    }
}
