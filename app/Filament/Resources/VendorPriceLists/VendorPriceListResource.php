<?php

namespace App\Filament\Resources\VendorPriceLists;

use App\Filament\Resources\VendorPriceLists\Pages\CreateVendorPriceList;
use App\Filament\Resources\VendorPriceLists\Pages\EditVendorPriceList;
use App\Filament\Resources\VendorPriceLists\Pages\ListVendorPriceLists;
use App\Filament\Resources\VendorPriceLists\Schemas\VendorPriceListForm;
use App\Filament\Resources\VendorPriceLists\Tables\VendorPriceListsTable;
use App\Models\VendorPriceList;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VendorPriceListResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '6. Vendor Management';
    protected static ?string $model = VendorPriceList::class;
    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VendorPriceListForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorPriceListsTable::configure($table);
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
            'index' => ListVendorPriceLists::route('/'),
            'create' => CreateVendorPriceList::route('/create'),
            'edit' => EditVendorPriceList::route('/{record}/edit'),
        ];
    }
}
