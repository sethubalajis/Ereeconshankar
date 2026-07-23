<?php

namespace App\Filament\Resources\UserBranchAccesses;

use App\Filament\Resources\UserBranchAccesses\Pages\CreateUserBranchAccess;
use App\Filament\Resources\UserBranchAccesses\Pages\EditUserBranchAccess;
use App\Filament\Resources\UserBranchAccesses\Pages\ListUserBranchAccesses;
use App\Filament\Resources\UserBranchAccesses\Schemas\UserBranchAccessForm;
use App\Filament\Resources\UserBranchAccesses\Tables\UserBranchAccessesTable;
use App\Models\UserBranchAccess;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserBranchAccessResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '2. User & Role Management';
    protected static ?string $model = UserBranchAccess::class;
    protected static ?int $navigationSort = 6;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    public static function form(Schema $schema): Schema
    {
        return UserBranchAccessForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserBranchAccessesTable::configure($table);
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
            'index' => ListUserBranchAccesses::route('/'),
            'create' => CreateUserBranchAccess::route('/create'),
            'edit' => EditUserBranchAccess::route('/{record}/edit'),
        ];
    }
}
