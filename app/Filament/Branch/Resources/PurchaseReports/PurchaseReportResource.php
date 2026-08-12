<?php

namespace App\Filament\Branch\Resources\PurchaseReports;

use App\Filament\Branch\Resources\PurchaseReports\Pages\CreatePurchaseReport;
use App\Filament\Branch\Resources\PurchaseReports\Pages\EditPurchaseReport;
use App\Filament\Branch\Resources\PurchaseReports\Pages\ListPurchaseReports;
use App\Filament\Branch\Resources\PurchaseReports\Schemas\PurchaseReportForm;
use App\Filament\Branch\Resources\PurchaseReports\Tables\PurchaseReportsTable;
use App\Models\PurchaseReport;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PurchaseReportResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '20. Reports & BI Dashboard';
    protected static ?int $navigationSort = 3;
    protected static ?string $model = PurchaseReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function form(Schema $schema): Schema
    {
        return PurchaseReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchaseReportsTable::configure($table);
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
            'index' => ListPurchaseReports::route('/'),
            'create' => CreatePurchaseReport::route('/create'),
            'edit' => EditPurchaseReport::route('/{record}/edit'),
        ];
    }
}
