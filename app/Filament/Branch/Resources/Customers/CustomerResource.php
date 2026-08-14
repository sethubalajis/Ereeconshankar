<?php

namespace App\Filament\Branch\Resources\Customers;

use App\Filament\Branch\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Branch\Resources\Customers\Pages\EditCustomer;
use App\Filament\Branch\Resources\Customers\Pages\ListCustomers;
use App\Filament\Branch\Resources\Customers\Schemas\CustomerForm;
use App\Filament\Branch\Resources\Customers\Tables\CustomersTable;
use App\Models\Customer;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '12. CRM & Loyalty';
    protected static ?int $navigationSort = 1;
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return CustomerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomersTable::configure($table);
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
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
