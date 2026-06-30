<?php

namespace App\Filament\Resources\ServiceReports;

use App\Filament\Resources\ServiceReports\Pages\CreateServiceReport;
use App\Filament\Resources\ServiceReports\Pages\EditServiceReport;
use App\Filament\Resources\ServiceReports\Pages\ListServiceReports;
use App\Filament\Resources\ServiceReports\Schemas\ServiceReportForm;
use App\Filament\Resources\ServiceReports\Tables\ServiceReportsTable;
use App\Models\ServiceReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceReportResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '19. Service & Complaints';
    protected static ?int $navigationSort = 7;
    protected static ?string $model = ServiceReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ServiceReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceReportsTable::configure($table);
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
            'index' => ListServiceReports::route('/'),
            'create' => CreateServiceReport::route('/create'),
            'edit' => EditServiceReport::route('/{record}/edit'),
        ];
    }
}
