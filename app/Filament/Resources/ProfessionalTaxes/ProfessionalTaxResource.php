<?php

namespace App\Filament\Resources\ProfessionalTaxes;

use App\Filament\Resources\ProfessionalTaxes\Pages\CreateProfessionalTax;
use App\Filament\Resources\ProfessionalTaxes\Pages\EditProfessionalTax;
use App\Filament\Resources\ProfessionalTaxes\Pages\ListProfessionalTaxes;
use App\Filament\Resources\ProfessionalTaxes\Schemas\ProfessionalTaxForm;
use App\Filament\Resources\ProfessionalTaxes\Tables\ProfessionalTaxesTable;
use App\Models\ProfessionalTax;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProfessionalTaxResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '17. Payroll';
    protected static ?int $navigationSort = 11;
    protected static ?string $model = ProfessionalTax::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return ProfessionalTaxForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProfessionalTaxesTable::configure($table);
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
            'index' => ListProfessionalTaxes::route('/'),
            'create' => CreateProfessionalTax::route('/create'),
            'edit' => EditProfessionalTax::route('/{record}/edit'),
        ];
    }
}
