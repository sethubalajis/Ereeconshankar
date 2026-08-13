<?php

namespace App\Filament\Branch\Resources\PayrollProcessings;

use App\Filament\Branch\Resources\PayrollProcessings\Pages\CreatePayrollProcessing;
use App\Filament\Branch\Resources\PayrollProcessings\Pages\EditPayrollProcessing;
use App\Filament\Branch\Resources\PayrollProcessings\Pages\ListPayrollProcessings;
use App\Filament\Branch\Resources\PayrollProcessings\Schemas\PayrollProcessingForm;
use App\Filament\Branch\Resources\PayrollProcessings\Tables\PayrollProcessingsTable;
use App\Models\PayrollProcessing;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PayrollProcessingResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '17. Payroll';
    protected static ?int $navigationSort = 13;
    protected static ?string $model = PayrollProcessing::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return PayrollProcessingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayrollProcessingsTable::configure($table);
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
            'index' => ListPayrollProcessings::route('/'),
            'create' => CreatePayrollProcessing::route('/create'),
            'edit' => EditPayrollProcessing::route('/{record}/edit'),
        ];
    }
}
