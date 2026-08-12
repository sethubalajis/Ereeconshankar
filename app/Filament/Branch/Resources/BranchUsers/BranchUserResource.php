<?php

namespace App\Filament\Branch\Resources\BranchUsers;

use App\Filament\Branch\Resources\BranchUsers\Pages\CreateBranchUser;
use App\Filament\Branch\Resources\BranchUsers\Pages\EditBranchUser;
use App\Filament\Branch\Resources\BranchUsers\Pages\ListBranchUsers;
use App\Filament\Branch\Resources\BranchUsers\Schemas\BranchUserForm;
use App\Filament\Branch\Resources\BranchUsers\Tables\BranchUsersTable;
use App\Models\BranchUser;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BranchUserResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '9. Branch Management';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = BranchUser::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    public static function form(Schema $schema): Schema
    {
        return BranchUserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BranchUsersTable::configure($table);
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
            'index' => ListBranchUsers::route('/'),
            'create' => CreateBranchUser::route('/create'),
            'edit' => EditBranchUser::route('/{record}/edit'),
        ];
    }
}
