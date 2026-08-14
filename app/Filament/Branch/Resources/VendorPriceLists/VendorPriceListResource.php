<?php

namespace App\Filament\Branch\Resources\VendorPriceLists;

use App\Filament\Branch\Resources\VendorPriceLists\Pages\CreateVendorPriceList;
use App\Filament\Branch\Resources\VendorPriceLists\Pages\EditVendorPriceList;
use App\Filament\Branch\Resources\VendorPriceLists\Pages\ListVendorPriceLists;
use App\Filament\Branch\Resources\VendorPriceLists\Schemas\VendorPriceListForm;
use App\Filament\Branch\Resources\VendorPriceLists\Tables\VendorPriceListsTable;
use App\Models\VendorPriceList;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VendorPriceListResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '6. Vendor Management';
    protected static ?string $model = VendorPriceList::class;
    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

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
