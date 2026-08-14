<?php

namespace App\Filament\Branch\Resources\FinancialReports;

use App\Filament\Branch\Resources\FinancialReports\Pages\CreateFinancialReport;
use App\Filament\Branch\Resources\FinancialReports\Pages\EditFinancialReport;
use App\Filament\Branch\Resources\FinancialReports\Pages\ListFinancialReports;
use App\Filament\Branch\Resources\FinancialReports\Schemas\FinancialReportForm;
use App\Filament\Branch\Resources\FinancialReports\Tables\FinancialReportsTable;
use App\Models\FinancialReport;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FinancialReportResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '20. Reports & BI Dashboard';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = FinancialReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return FinancialReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FinancialReportsTable::configure($table);
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
            'index' => ListFinancialReports::route('/'),
            'create' => CreateFinancialReport::route('/create'),
            'edit' => EditFinancialReport::route('/{record}/edit'),
        ];
    }
}
