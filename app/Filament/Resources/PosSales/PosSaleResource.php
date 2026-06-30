<?php

namespace App\Filament\Resources\PosSales;

use App\Filament\Resources\PosSales\Pages\CreatePosSale;
use App\Filament\Resources\PosSales\Pages\EditPosSale;
use App\Filament\Resources\PosSales\Pages\ListPosSales;
use App\Filament\Resources\PosSales\Schemas\PosSaleForm;
use App\Filament\Resources\PosSales\Tables\PosSalesTable;
use App\Models\PosSale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PosSaleResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '11. Retail POS';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = PosSale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PosSaleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PosSalesTable::configure($table);
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
            'index' => ListPosSales::route('/'),
            'create' => CreatePosSale::route('/create'),
            'edit' => EditPosSale::route('/{record}/edit'),
        ];
    }
}
