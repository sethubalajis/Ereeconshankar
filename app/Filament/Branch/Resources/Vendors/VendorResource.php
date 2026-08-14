<?php

namespace App\Filament\Branch\Resources\Vendors;

use App\Filament\Branch\Resources\Vendors\Pages\CreateVendor;
use App\Filament\Branch\Resources\Vendors\Pages\EditVendor;
use App\Filament\Branch\Resources\Vendors\Pages\ListVendors;
use App\Filament\Branch\Resources\Vendors\Schemas\VendorForm;
use App\Filament\Branch\Resources\Vendors\Tables\VendorsTable;
use App\Models\Vendor;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VendorResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '6. Vendor Management';
    protected static ?string $model = Vendor::class;
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function form(Schema $schema): Schema
    {
        return VendorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorsTable::configure($table);
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
            'index' => ListVendors::route('/'),
            'create' => CreateVendor::route('/create'),
            'edit' => EditVendor::route('/{record}/edit'),
        ];
    }
}
