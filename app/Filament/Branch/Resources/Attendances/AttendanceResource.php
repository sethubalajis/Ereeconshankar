<?php

namespace App\Filament\Branch\Resources\Attendances;

use App\Filament\Branch\Resources\Attendances\Pages\CreateAttendance;
use App\Filament\Branch\Resources\Attendances\Pages\EditAttendance;
use App\Filament\Branch\Resources\Attendances\Pages\ListAttendances;
use App\Filament\Branch\Resources\Attendances\Schemas\AttendanceForm;
use App\Filament\Branch\Resources\Attendances\Tables\AttendancesTable;
use App\Models\Attendance;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttendanceResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '16. Human Resources';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = Attendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return AttendanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendancesTable::configure($table);
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
            'index' => ListAttendances::route('/'),
            'create' => CreateAttendance::route('/create'),
            'edit' => EditAttendance::route('/{record}/edit'),
        ];
    }
}
