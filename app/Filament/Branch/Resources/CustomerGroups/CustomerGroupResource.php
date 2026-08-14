<?php

namespace App\Filament\Branch\Resources\CustomerGroups;

use App\Filament\Branch\Resources\CustomerGroups\Pages\CreateCustomerGroup;
use App\Filament\Branch\Resources\CustomerGroups\Pages\EditCustomerGroup;
use App\Filament\Branch\Resources\CustomerGroups\Pages\ListCustomerGroups;
use App\Filament\Branch\Resources\CustomerGroups\Schemas\CustomerGroupForm;
use App\Filament\Branch\Resources\CustomerGroups\Tables\CustomerGroupsTable;
use App\Models\CustomerGroup;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerGroupResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '12. CRM & Loyalty';
    protected static ?int $navigationSort = 2;
    protected static ?string $model = CustomerGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return CustomerGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerGroupsTable::configure($table);
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
            'index' => ListCustomerGroups::route('/'),
            'create' => CreateCustomerGroup::route('/create'),
            'edit' => EditCustomerGroup::route('/{record}/edit'),
        ];
    }
}
