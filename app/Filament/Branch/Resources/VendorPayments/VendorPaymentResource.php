<?php

namespace App\Filament\Branch\Resources\VendorPayments;

use App\Filament\Branch\Resources\VendorPayments\Pages\CreateVendorPayment;
use App\Filament\Branch\Resources\VendorPayments\Pages\EditVendorPayment;
use App\Filament\Branch\Resources\VendorPayments\Pages\ListVendorPayments;
use App\Filament\Branch\Resources\VendorPayments\Schemas\VendorPaymentForm;
use App\Filament\Branch\Resources\VendorPayments\Tables\VendorPaymentsTable;
use App\Models\VendorPayment;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VendorPaymentResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '6. Vendor Management';
    protected static ?string $model = VendorPayment::class;
    protected static ?int $navigationSort = 7;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function form(Schema $schema): Schema
    {
        return VendorPaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorPaymentsTable::configure($table);
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
            'index' => ListVendorPayments::route('/'),
            'create' => CreateVendorPayment::route('/create'),
            'edit' => EditVendorPayment::route('/{record}/edit'),
        ];
    }
}
