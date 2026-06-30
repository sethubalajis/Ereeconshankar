<?php

namespace App\Filament\Resources\GstReports;

use App\Filament\Resources\GstReports\Pages\CreateGstReport;
use App\Filament\Resources\GstReports\Pages\EditGstReport;
use App\Filament\Resources\GstReports\Pages\ListGstReports;
use App\Filament\Resources\GstReports\Schemas\GstReportForm;
use App\Filament\Resources\GstReports\Tables\GstReportsTable;
use App\Models\GstReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GstReportResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '20. Reports & BI Dashboard';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = GstReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
