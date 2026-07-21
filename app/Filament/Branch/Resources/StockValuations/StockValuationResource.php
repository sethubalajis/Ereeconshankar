<?php

namespace App\Filament\Branch\Resources\StockValuations;

use App\Filament\Branch\Resources\StockValuations\Pages\CreateStockValuation;
use App\Filament\Branch\Resources\StockValuations\Pages\EditStockValuation;
use App\Filament\Branch\Resources\StockValuations\Pages\ListStockValuations;
use App\Filament\Branch\Resources\StockValuations\Schemas\StockValuationForm;
use App\Filament\Branch\Resources\StockValuations\Tables\StockValuationsTable;
use App\Models\StockValuation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockValuationResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '7. Inventory Management';
    protected static ?string $model = StockValuation::class;
    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // Removed canCreate and canEdit to allow these actions

    public static function form(Schema $schema): Schema
    {
        return StockValuationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockValuationsTable::configure($table);
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
            'index' => ListStockValuations::route('/'),
            'create' => CreateStockValuation::route('/create'),
            'edit' => EditStockValuation::route('/{record}/edit'),
        ];
    }
}
