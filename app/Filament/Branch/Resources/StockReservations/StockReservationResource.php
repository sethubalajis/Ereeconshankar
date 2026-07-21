<?php

namespace App\Filament\Branch\Resources\StockReservations;

use App\Filament\Branch\Resources\StockReservations\Pages\CreateStockReservation;
use App\Filament\Branch\Resources\StockReservations\Pages\EditStockReservation;
use App\Filament\Branch\Resources\StockReservations\Pages\ListStockReservations;
use App\Filament\Branch\Resources\StockReservations\Schemas\StockReservationForm;
use App\Filament\Branch\Resources\StockReservations\Tables\StockReservationsTable;
use App\Models\StockReservation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockReservationResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '7. Inventory Management';
    protected static ?string $model = StockReservation::class;
    protected static ?int $navigationSort = 6;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StockReservationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockReservationsTable::configure($table);
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
            'index' => ListStockReservations::route('/'),
            'create' => CreateStockReservation::route('/create'),
            'edit' => EditStockReservation::route('/{record}/edit'),
        ];
    }
}
