<?php

namespace App\Filament\Branch\Resources\StockAdjustments;

use App\Filament\Branch\Resources\StockAdjustments\Pages\CreateStockAdjustment;
use App\Filament\Branch\Resources\StockAdjustments\Pages\EditStockAdjustment;
use App\Filament\Branch\Resources\StockAdjustments\Pages\ListStockAdjustments;
use App\Filament\Branch\Resources\StockAdjustments\Schemas\StockAdjustmentForm;
use App\Filament\Branch\Resources\StockAdjustments\Tables\StockAdjustmentsTable;
use App\Models\StockAdjustment;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockAdjustmentResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '7. Inventory Management';
    protected static ?string $model = StockAdjustment::class;
    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    public static function form(Schema $schema): Schema
    {
        return StockAdjustmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockAdjustmentsTable::configure($table);
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
            'index' => ListStockAdjustments::route('/'),
            'create' => CreateStockAdjustment::route('/create'),
            'edit' => EditStockAdjustment::route('/{record}/edit'),
        ];
    }
}
