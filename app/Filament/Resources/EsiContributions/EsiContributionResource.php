<?php

namespace App\Filament\Resources\EsiContributions;

use App\Filament\Resources\EsiContributions\Pages\CreateEsiContribution;
use App\Filament\Resources\EsiContributions\Pages\EditEsiContribution;
use App\Filament\Resources\EsiContributions\Pages\ListEsiContributions;
use App\Filament\Resources\EsiContributions\Schemas\EsiContributionForm;
use App\Filament\Resources\EsiContributions\Tables\EsiContributionsTable;
use App\Models\EsiContribution;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EsiContributionResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '17. Payroll';
    protected static ?int $navigationSort = 10;
    protected static ?string $model = EsiContribution::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return EsiContributionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EsiContributionsTable::configure($table);
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
            'index' => ListEsiContributions::route('/'),
            'create' => CreateEsiContribution::route('/create'),
            'edit' => EditEsiContribution::route('/{record}/edit'),
        ];
    }
}
