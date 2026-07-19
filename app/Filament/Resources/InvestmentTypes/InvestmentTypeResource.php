<?php

namespace App\Filament\Resources\InvestmentTypes;

use App\Filament\Resources\InvestmentTypes\Pages\CreateInvestmentType;
use App\Filament\Resources\InvestmentTypes\Pages\EditInvestmentType;
use App\Filament\Resources\InvestmentTypes\Pages\ListInvestmentTypes;
use App\Filament\Resources\InvestmentTypes\Schemas\InvestmentTypeForm;
use App\Filament\Resources\InvestmentTypes\Tables\InvestmentTypesTable;
use App\Models\InvestmentType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InvestmentTypeResource extends Resource
{
    protected static ?string $model = InvestmentType::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = '17. Payroll';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return InvestmentTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvestmentTypesTable::configure($table);
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
            'index' => ListInvestmentTypes::route('/'),
            'create' => CreateInvestmentType::route('/create'),
            'edit' => EditInvestmentType::route('/{record}/edit'),
        ];
    }
}
