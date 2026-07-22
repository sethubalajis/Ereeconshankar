<?php

namespace App\Filament\Branch\Resources\PosSaleItems;

use App\Filament\Branch\Resources\PosSaleItems\Pages\CreatePosSaleItem;
use App\Filament\Branch\Resources\PosSaleItems\Pages\EditPosSaleItem;
use App\Filament\Branch\Resources\PosSaleItems\Pages\ListPosSaleItems;
use App\Filament\Branch\Resources\PosSaleItems\Schemas\PosSaleItemForm;
use App\Filament\Branch\Resources\PosSaleItems\Tables\PosSaleItemsTable;
use App\Models\PosSaleItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PosSaleItemResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '11. Retail POS';
    protected static ?int $navigationSort = 7;
    protected static ?string $model = PosSaleItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PosSaleItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PosSaleItemsTable::configure($table);
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
            'index' => ListPosSaleItems::route('/'),
            'create' => CreatePosSaleItem::route('/create'),
            'edit' => EditPosSaleItem::route('/{record}/edit'),
        ];
    }
}
