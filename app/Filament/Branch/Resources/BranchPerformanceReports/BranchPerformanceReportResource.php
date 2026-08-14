<?php

namespace App\Filament\Branch\Resources\BranchPerformanceReports;

use App\Filament\Branch\Resources\BranchPerformanceReports\Pages\CreateBranchPerformanceReport;
use App\Filament\Branch\Resources\BranchPerformanceReports\Pages\EditBranchPerformanceReport;
use App\Filament\Branch\Resources\BranchPerformanceReports\Pages\ListBranchPerformanceReports;
use App\Filament\Branch\Resources\BranchPerformanceReports\Schemas\BranchPerformanceReportForm;
use App\Filament\Branch\Resources\BranchPerformanceReports\Tables\BranchPerformanceReportsTable;
use App\Models\BranchPerformanceReport;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BranchPerformanceReportResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '20. Reports & BI Dashboard';
    protected static ?int $navigationSort = 8;
    protected static ?string $model = BranchPerformanceReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    public static function form(Schema $schema): Schema
    {
        return BranchPerformanceReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BranchPerformanceReportsTable::configure($table);
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
            'index' => ListBranchPerformanceReports::route('/'),
            'create' => CreateBranchPerformanceReport::route('/create'),
            'edit' => EditBranchPerformanceReport::route('/{record}/edit'),
        ];
    }
}
