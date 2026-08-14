<?php

namespace App\Filament\Branch\Resources\SalesReports;

use App\Filament\Branch\Resources\SalesReports\Pages\CreateSalesReport;
use App\Filament\Branch\Resources\SalesReports\Pages\EditSalesReport;
use App\Filament\Branch\Resources\SalesReports\Pages\ListSalesReports;
use App\Filament\Branch\Resources\SalesReports\Schemas\SalesReportForm;
use App\Filament\Branch\Resources\SalesReports\Tables\SalesReportsTable;
use App\Models\SalesReport;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalesReportResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '20. Reports & BI Dashboard';
    protected static ?int $navigationSort = 2;
    protected static ?string $model = SalesReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    public static function form(Schema $schema): Schema
    {
        return SalesReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesReportsTable::configure($table);
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
            'index' => ListSalesReports::route('/'),
            'create' => CreateSalesReport::route('/create'),
            'edit' => EditSalesReport::route('/{record}/edit'),
        ];
    }
}
