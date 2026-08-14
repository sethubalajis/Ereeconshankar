<?php

namespace App\Filament\Branch\Resources\VendorPerformances;

use App\Filament\Branch\Resources\VendorPerformances\Pages\CreateVendorPerformance;
use App\Filament\Branch\Resources\VendorPerformances\Pages\EditVendorPerformance;
use App\Filament\Branch\Resources\VendorPerformances\Pages\ListVendorPerformances;
use App\Filament\Branch\Resources\VendorPerformances\Schemas\VendorPerformanceForm;
use App\Filament\Branch\Resources\VendorPerformances\Tables\VendorPerformancesTable;
use App\Models\VendorPerformance;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VendorPerformanceResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '6. Vendor Management';
    protected static ?string $model = VendorPerformance::class;
    protected static ?int $navigationSort = 6;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function form(Schema $schema): Schema
    {
        return VendorPerformanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorPerformancesTable::configure($table);
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
            'index' => ListVendorPerformances::route('/'),
            'create' => CreateVendorPerformance::route('/create'),
            'edit' => EditVendorPerformance::route('/{record}/edit'),
        ];
    }
}
