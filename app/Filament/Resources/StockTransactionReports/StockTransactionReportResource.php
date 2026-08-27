<?php

namespace App\Filament\Resources\StockTransactionReports;

use App\Filament\Resources\StockTransactionReports\Pages\CreateStockTransactionReport;
use App\Filament\Resources\StockTransactionReports\Pages\EditStockTransactionReport;
use App\Filament\Resources\StockTransactionReports\Pages\ListStockTransactionReports;
use App\Filament\Resources\StockTransactionReports\Schemas\StockTransactionReportForm;
use App\Filament\Resources\StockTransactionReports\Tables\StockTransactionReportsTable;
use App\Models\StockTransactionReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockTransactionReportResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '20. Reports & BI Dashboard';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = StockTransactionReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    public static function form(Schema $schema): Schema
    {
        return StockTransactionReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockTransactionReportsTable::configure($table);
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
            'index' => ListStockTransactionReports::route('/'),
            'create' => CreateStockTransactionReport::route('/create'),
            'edit' => EditStockTransactionReport::route('/{record}/edit'),
        ];
    }
}
