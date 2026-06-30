<?php

namespace App\Filament\Resources\AccountGroups;

use App\Filament\Resources\AccountGroups\Pages\CreateAccountGroup;
use App\Filament\Resources\AccountGroups\Pages\EditAccountGroup;
use App\Filament\Resources\AccountGroups\Pages\ListAccountGroups;
use App\Filament\Resources\AccountGroups\Schemas\AccountGroupForm;
use App\Filament\Resources\AccountGroups\Tables\AccountGroupsTable;
use App\Models\AccountGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AccountGroupResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '14. Finance & Accounts';
    protected static ?int $navigationSort = 2;
    protected static ?string $model = AccountGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AccountGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AccountGroupsTable::configure($table);
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
            'index' => ListAccountGroups::route('/'),
            'create' => CreateAccountGroup::route('/create'),
            'edit' => EditAccountGroup::route('/{record}/edit'),
        ];
    }
}
