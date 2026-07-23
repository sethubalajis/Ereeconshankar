<?php

namespace App\Filament\Resources\BinStocks;

use App\Filament\Resources\BinStocks\Pages\CreateBinStock;
use App\Filament\Resources\BinStocks\Pages\EditBinStock;
use App\Filament\Resources\BinStocks\Pages\ListBinStocks;
use App\Filament\Resources\BinStocks\Schemas\BinStockForm;
use App\Filament\Resources\BinStocks\Tables\BinStocksTable;
use App\Models\BinStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BinStockResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '8. Warehouse Management';
    protected static ?string $model = BinStock::class;
    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    public static function form(Schema $schema): Schema
    {
        return BinStockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BinStocksTable::configure($table);
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
            'index' => ListBinStocks::route('/'),
            'create' => CreateBinStock::route('/create'),
            'edit' => EditBinStock::route('/{record}/edit'),
        ];
    }
}
