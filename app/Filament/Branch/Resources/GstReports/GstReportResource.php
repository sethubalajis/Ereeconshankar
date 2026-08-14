<?php

namespace App\Filament\Branch\Resources\GstReports;

use App\Filament\Branch\Resources\GstReports\Pages\CreateGstReport;
use App\Filament\Branch\Resources\GstReports\Pages\EditGstReport;
use App\Filament\Branch\Resources\GstReports\Pages\ListGstReports;
use App\Filament\Branch\Resources\GstReports\Schemas\GstReportForm;
use App\Filament\Branch\Resources\GstReports\Tables\GstReportsTable;
use App\Models\GstReport;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GstReportResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '20. Reports & BI Dashboard';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = GstReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    public static function form(Schema $schema): Schema
    {
        return GstReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GstReportsTable::configure($table);
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
            'index' => ListGstReports::route('/'),
            'create' => CreateGstReport::route('/create'),
            'edit' => EditGstReport::route('/{record}/edit'),
        ];
    }
}
