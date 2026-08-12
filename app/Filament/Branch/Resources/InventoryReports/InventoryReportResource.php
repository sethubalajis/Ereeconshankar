<?php

namespace App\Filament\Branch\Resources\InventoryReports;

use App\Filament\Branch\Resources\InventoryReports\Pages\CreateInventoryReport;
use App\Filament\Branch\Resources\InventoryReports\Pages\EditInventoryReport;
use App\Filament\Branch\Resources\InventoryReports\Pages\ListInventoryReports;
use App\Filament\Branch\Resources\InventoryReports\Schemas\InventoryReportForm;
use App\Filament\Branch\Resources\InventoryReports\Tables\InventoryReportsTable;
use App\Models\InventoryReport;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventoryReportResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '20. Reports & BI Dashboard';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = InventoryReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    public static function form(Schema $schema): Schema
    {
        return InventoryReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoryReportsTable::configure($table);
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
            'index' => ListInventoryReports::route('/'),
            'create' => CreateInventoryReport::route('/create'),
            'edit' => EditInventoryReport::route('/{record}/edit'),
        ];
    }
}
