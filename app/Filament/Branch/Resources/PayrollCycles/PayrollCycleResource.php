<?php

namespace App\Filament\Branch\Resources\PayrollCycles;

use App\Filament\Branch\Resources\PayrollCycles\Pages\CreatePayrollCycle;
use App\Filament\Branch\Resources\PayrollCycles\Pages\EditPayrollCycle;
use App\Filament\Branch\Resources\PayrollCycles\Pages\ListPayrollCycles;
use App\Filament\Branch\Resources\PayrollCycles\Schemas\PayrollCycleForm;
use App\Filament\Branch\Resources\PayrollCycles\Tables\PayrollCyclesTable;
use App\Models\PayrollCycle;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PayrollCycleResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '17. Payroll';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = PayrollCycle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return PayrollCycleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayrollCyclesTable::configure($table);
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
            'index' => ListPayrollCycles::route('/'),
            'create' => CreatePayrollCycle::route('/create'),
            'edit' => EditPayrollCycle::route('/{record}/edit'),
        ];
    }
}
