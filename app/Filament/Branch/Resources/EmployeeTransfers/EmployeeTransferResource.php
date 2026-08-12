<?php

namespace App\Filament\Branch\Resources\EmployeeTransfers;

use App\Filament\Branch\Resources\EmployeeTransfers\Pages\CreateEmployeeTransfer;
use App\Filament\Branch\Resources\EmployeeTransfers\Pages\EditEmployeeTransfer;
use App\Filament\Branch\Resources\EmployeeTransfers\Pages\ListEmployeeTransfers;
use App\Filament\Branch\Resources\EmployeeTransfers\Schemas\EmployeeTransferForm;
use App\Filament\Branch\Resources\EmployeeTransfers\Tables\EmployeeTransfersTable;
use App\Models\EmployeeTransfer;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmployeeTransferResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '16. Human Resources';
    protected static ?int $navigationSort = 10;
    protected static ?string $model = EmployeeTransfer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return EmployeeTransferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeTransfersTable::configure($table);
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
            'index' => ListEmployeeTransfers::route('/'),
            'create' => CreateEmployeeTransfer::route('/create'),
            'edit' => EditEmployeeTransfer::route('/{record}/edit'),
        ];
    }
}
