<?php

namespace App\Filament\Branch\Resources\EmployeeTaxDeclarations;

use App\Filament\Branch\Resources\EmployeeTaxDeclarations\Pages\CreateEmployeeTaxDeclaration;
use App\Filament\Branch\Resources\EmployeeTaxDeclarations\Pages\EditEmployeeTaxDeclaration;
use App\Filament\Branch\Resources\EmployeeTaxDeclarations\Pages\ListEmployeeTaxDeclarations;
use App\Filament\Branch\Resources\EmployeeTaxDeclarations\Schemas\EmployeeTaxDeclarationForm;
use App\Filament\Branch\Resources\EmployeeTaxDeclarations\Tables\EmployeeTaxDeclarationsTable;
use App\Models\EmployeeTaxDeclaration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmployeeTaxDeclarationResource extends Resource
{
    protected static ?string $model = EmployeeTaxDeclaration::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = '17. Payroll';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return EmployeeTaxDeclarationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeTaxDeclarationsTable::configure($table);
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
            'index' => ListEmployeeTaxDeclarations::route('/'),
            'create' => CreateEmployeeTaxDeclaration::route('/create'),
            'edit' => EditEmployeeTaxDeclaration::route('/{record}/edit'),
        ];
    }
}
