<?php

namespace App\Filament\Branch\Resources\CustomerWallets;

use App\Filament\Branch\Resources\CustomerWallets\Pages\CreateCustomerWallet;
use App\Filament\Branch\Resources\CustomerWallets\Pages\EditCustomerWallet;
use App\Filament\Branch\Resources\CustomerWallets\Pages\ListCustomerWallets;
use App\Filament\Branch\Resources\CustomerWallets\Schemas\CustomerWalletForm;
use App\Filament\Branch\Resources\CustomerWallets\Tables\CustomerWalletsTable;
use App\Models\CustomerWallet;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerWalletResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '12. CRM & Loyalty';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = CustomerWallet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return CustomerWalletForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerWalletsTable::configure($table);
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
            'index' => ListCustomerWallets::route('/'),
            'create' => CreateCustomerWallet::route('/create'),
            'edit' => EditCustomerWallet::route('/{record}/edit'),
        ];
    }
}
