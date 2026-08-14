<?php

namespace App\Filament\Branch\Resources\Shifts;

use App\Filament\Branch\Resources\Shifts\Pages\CreateShift;
use App\Filament\Branch\Resources\Shifts\Pages\EditShift;
use App\Filament\Branch\Resources\Shifts\Pages\ListShifts;
use App\Filament\Branch\Resources\Shifts\Schemas\ShiftForm;
use App\Filament\Branch\Resources\Shifts\Tables\ShiftsTable;
use App\Models\Shift;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShiftResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '16. Human Resources';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = Shift::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return ShiftForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShiftsTable::configure($table);
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
            'index' => ListShifts::route('/'),
            'create' => CreateShift::route('/create'),
            'edit' => EditShift::route('/{record}/edit'),
        ];
    }
}
