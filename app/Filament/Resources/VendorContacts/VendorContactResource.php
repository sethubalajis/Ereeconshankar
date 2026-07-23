<?php

namespace App\Filament\Resources\VendorContacts;

use App\Filament\Resources\VendorContacts\Pages\CreateVendorContact;
use App\Filament\Resources\VendorContacts\Pages\EditVendorContact;
use App\Filament\Resources\VendorContacts\Pages\ListVendorContacts;
use App\Filament\Resources\VendorContacts\Schemas\VendorContactForm;
use App\Filament\Resources\VendorContacts\Tables\VendorContactsTable;
use App\Models\VendorContact;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VendorContactResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '6. Vendor Management';
    protected static ?string $model = VendorContact::class;
    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function form(Schema $schema): Schema
    {
        return VendorContactForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorContactsTable::configure($table);
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
            'index' => ListVendorContacts::route('/'),
            'create' => CreateVendorContact::route('/create'),
            'edit' => EditVendorContact::route('/{record}/edit'),
        ];
    }
}
