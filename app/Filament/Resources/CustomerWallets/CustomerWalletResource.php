<?php

namespace App\Filament\Resources\CustomerWallets;

use App\Filament\Resources\CustomerWallets\Pages\CreateCustomerWallet;
use App\Filament\Resources\CustomerWallets\Pages\EditCustomerWallet;
use App\Filament\Resources\CustomerWallets\Pages\ListCustomerWallets;
use App\Filament\Resources\CustomerWallets\Schemas\CustomerWalletForm;
use App\Filament\Resources\CustomerWallets\Tables\CustomerWalletsTable;
use App\Models\CustomerWallet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerWalletResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '12. CRM & Loyalty';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = CustomerWallet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
