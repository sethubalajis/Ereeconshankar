<?php

namespace App\Filament\Branch\Resources\PosSales;

use App\Filament\Branch\Resources\PosSales\Pages\CreatePosSale;
use App\Filament\Branch\Resources\PosSales\Pages\EditPosSale;
use App\Filament\Branch\Resources\PosSales\Pages\ListPosSales;
use App\Filament\Branch\Resources\PosSales\Schemas\PosSaleForm;
use App\Filament\Branch\Resources\PosSales\Tables\PosSalesTable;
use App\Models\PosSale;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PosSaleResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '11. Retail POS';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = PosSale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

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
