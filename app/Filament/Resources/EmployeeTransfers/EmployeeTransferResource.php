<?php

namespace App\Filament\Resources\EmployeeTransfers;

use App\Filament\Resources\EmployeeTransfers\Pages\CreateEmployeeTransfer;
use App\Filament\Resources\EmployeeTransfers\Pages\EditEmployeeTransfer;
use App\Filament\Resources\EmployeeTransfers\Pages\ListEmployeeTransfers;
use App\Filament\Resources\EmployeeTransfers\Schemas\EmployeeTransferForm;
use App\Filament\Resources\EmployeeTransfers\Tables\EmployeeTransfersTable;
use App\Models\EmployeeTransfer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmployeeTransferResource extends Resource
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
