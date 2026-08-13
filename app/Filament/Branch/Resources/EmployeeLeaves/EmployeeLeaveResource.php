<?php

namespace App\Filament\Branch\Resources\EmployeeLeaves;

use App\Filament\Branch\Resources\EmployeeLeaves\Pages\CreateEmployeeLeave;
use App\Filament\Branch\Resources\EmployeeLeaves\Pages\EditEmployeeLeave;
use App\Filament\Branch\Resources\EmployeeLeaves\Pages\ListEmployeeLeaves;
use App\Filament\Branch\Resources\EmployeeLeaves\Schemas\EmployeeLeaveForm;
use App\Filament\Branch\Resources\EmployeeLeaves\Tables\EmployeeLeavesTable;
use App\Models\EmployeeLeave;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmployeeLeaveResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '16. Human Resources';
    protected static ?int $navigationSort = 8;
    protected static ?string $model = EmployeeLeave::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return EmployeeLeaveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeLeavesTable::configure($table);
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
            'index' => ListEmployeeLeaves::route('/'),
            'create' => CreateEmployeeLeave::route('/create'),
            'edit' => EditEmployeeLeave::route('/{record}/edit'),
        ];
    }
}
