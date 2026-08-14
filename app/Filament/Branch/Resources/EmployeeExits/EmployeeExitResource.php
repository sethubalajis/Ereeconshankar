<?php

namespace App\Filament\Branch\Resources\EmployeeExits;

use App\Filament\Branch\Resources\EmployeeExits\Pages\CreateEmployeeExit;
use App\Filament\Branch\Resources\EmployeeExits\Pages\EditEmployeeExit;
use App\Filament\Branch\Resources\EmployeeExits\Pages\ListEmployeeExits;
use App\Filament\Branch\Resources\EmployeeExits\Schemas\EmployeeExitForm;
use App\Filament\Branch\Resources\EmployeeExits\Tables\EmployeeExitsTable;
use App\Models\EmployeeExit;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmployeeExitResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '16. Human Resources';
    protected static ?int $navigationSort = 11;
    protected static ?string $model = EmployeeExit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return EmployeeExitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeExitsTable::configure($table);
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
            'index' => ListEmployeeExits::route('/'),
            'create' => CreateEmployeeExit::route('/create'),
            'edit' => EditEmployeeExit::route('/{record}/edit'),
        ];
    }
}
