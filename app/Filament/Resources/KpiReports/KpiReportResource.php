<?php

namespace App\Filament\Resources\KpiReports;

use App\Filament\Resources\KpiReports\Pages\CreateKpiReport;
use App\Filament\Resources\KpiReports\Pages\EditKpiReport;
use App\Filament\Resources\KpiReports\Pages\ListKpiReports;
use App\Filament\Resources\KpiReports\Schemas\KpiReportForm;
use App\Filament\Resources\KpiReports\Tables\KpiReportsTable;
use App\Models\KpiReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KpiReportResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '20. Reports & BI Dashboard';
    protected static ?int $navigationSort = 9;
    protected static ?string $model = KpiReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    public static function form(Schema $schema): Schema
    {
        return KpiReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KpiReportsTable::configure($table);
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
            'index' => ListKpiReports::route('/'),
            'create' => CreateKpiReport::route('/create'),
            'edit' => EditKpiReport::route('/{record}/edit'),
        ];
    }
}
