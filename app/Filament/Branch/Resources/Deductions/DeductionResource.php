<?php

namespace App\Filament\Branch\Resources\Deductions;

use App\Filament\Branch\Resources\Deductions\Pages\CreateDeduction;
use App\Filament\Branch\Resources\Deductions\Pages\EditDeduction;
use App\Filament\Branch\Resources\Deductions\Pages\ListDeductions;
use App\Filament\Branch\Resources\Deductions\Schemas\DeductionForm;
use App\Filament\Branch\Resources\Deductions\Tables\DeductionsTable;
use App\Models\Deduction;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeductionResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '17. Payroll';
    protected static ?int $navigationSort = 8;
    protected static ?string $model = Deduction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return DeductionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeductionsTable::configure($table);
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
            'index' => ListDeductions::route('/'),
            'create' => CreateDeduction::route('/create'),
            'edit' => EditDeduction::route('/{record}/edit'),
        ];
    }
}
