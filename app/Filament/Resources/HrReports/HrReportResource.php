<?php

namespace App\Filament\Resources\HrReports;

use App\Filament\Resources\HrReports\Pages\CreateHrReport;
use App\Filament\Resources\HrReports\Pages\EditHrReport;
use App\Filament\Resources\HrReports\Pages\ListHrReports;
use App\Filament\Resources\HrReports\Schemas\HrReportForm;
use App\Filament\Resources\HrReports\Tables\HrReportsTable;
use App\Models\HrReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HrReportResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '20. Reports & BI Dashboard';
    protected static ?int $navigationSort = 7;
    protected static ?string $model = HrReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return HrReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HrReportsTable::configure($table);
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
            'index' => ListHrReports::route('/'),
            'create' => CreateHrReport::route('/create'),
            'edit' => EditHrReport::route('/{record}/edit'),
        ];
    }
}
