<?php

namespace App\Filament\Branch\Resources\SalaryComponents;

use App\Filament\Branch\Resources\SalaryComponents\Pages\CreateSalaryComponent;
use App\Filament\Branch\Resources\SalaryComponents\Pages\EditSalaryComponent;
use App\Filament\Branch\Resources\SalaryComponents\Pages\ListSalaryComponents;
use App\Filament\Branch\Resources\SalaryComponents\Schemas\SalaryComponentForm;
use App\Filament\Branch\Resources\SalaryComponents\Tables\SalaryComponentsTable;
use App\Models\SalaryComponent;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalaryComponentResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '17. Payroll';
    protected static ?int $navigationSort = 2;
    protected static ?string $model = SalaryComponent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return SalaryComponentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalaryComponentsTable::configure($table);
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
            'index' => ListSalaryComponents::route('/'),
            'create' => CreateSalaryComponent::route('/create'),
            'edit' => EditSalaryComponent::route('/{record}/edit'),
        ];
    }
}
