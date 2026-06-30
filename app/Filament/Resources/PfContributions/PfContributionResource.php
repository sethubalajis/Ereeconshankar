<?php

namespace App\Filament\Resources\PfContributions;

use App\Filament\Resources\PfContributions\Pages\CreatePfContribution;
use App\Filament\Resources\PfContributions\Pages\EditPfContribution;
use App\Filament\Resources\PfContributions\Pages\ListPfContributions;
use App\Filament\Resources\PfContributions\Schemas\PfContributionForm;
use App\Filament\Resources\PfContributions\Tables\PfContributionsTable;
use App\Models\PfContribution;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PfContributionResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '17. Payroll';
    protected static ?int $navigationSort = 9;
    protected static ?string $model = PfContribution::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PfContributionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PfContributionsTable::configure($table);
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
            'index' => ListPfContributions::route('/'),
            'create' => CreatePfContribution::route('/create'),
            'edit' => EditPfContribution::route('/{record}/edit'),
        ];
    }
}
