<?php

namespace App\Filament\Branch\Resources\CustomerMemberships;

use App\Filament\Branch\Resources\CustomerMemberships\Pages\CreateCustomerMembership;
use App\Filament\Branch\Resources\CustomerMemberships\Pages\EditCustomerMembership;
use App\Filament\Branch\Resources\CustomerMemberships\Pages\ListCustomerMemberships;
use App\Filament\Branch\Resources\CustomerMemberships\Schemas\CustomerMembershipForm;
use App\Filament\Branch\Resources\CustomerMemberships\Tables\CustomerMembershipsTable;
use App\Models\CustomerMembership;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerMembershipResource extends BaseBranchResource
{
    protected static ?string $model = CustomerMembership::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static \UnitEnum|string|null $navigationGroup = '12. CRM & Loyalty';
    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return CustomerMembershipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerMembershipsTable::configure($table);
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
            'index' => ListCustomerMemberships::route('/'),
            'create' => CreateCustomerMembership::route('/create'),
            'edit' => EditCustomerMembership::route('/{record}/edit'),
        ];
    }
}
