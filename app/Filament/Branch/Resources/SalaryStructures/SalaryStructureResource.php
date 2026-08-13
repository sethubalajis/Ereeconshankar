<?php

namespace App\Filament\Branch\Resources\SalaryStructures;

use App\Filament\Branch\Resources\SalaryStructures\Pages\CreateSalaryStructure;
use App\Filament\Branch\Resources\SalaryStructures\Pages\EditSalaryStructure;
use App\Filament\Branch\Resources\SalaryStructures\Pages\ListSalaryStructures;
use App\Filament\Branch\Resources\SalaryStructures\Schemas\SalaryStructureForm;
use App\Filament\Branch\Resources\SalaryStructures\Tables\SalaryStructuresTable;
use App\Models\SalaryStructure;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalaryStructureResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '17. Payroll';
    protected static ?int $navigationSort = 1;
    protected static ?string $model = SalaryStructure::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return SalaryStructureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalaryStructuresTable::configure($table);
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
            'index' => ListSalaryStructures::route('/'),
            'create' => CreateSalaryStructure::route('/create'),
            'edit' => EditSalaryStructure::route('/{record}/edit'),
        ];
    }
}
