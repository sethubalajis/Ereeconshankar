<?php

namespace App\Filament\Resources\Incentives;

use App\Filament\Resources\Incentives\Pages\CreateIncentive;
use App\Filament\Resources\Incentives\Pages\EditIncentive;
use App\Filament\Resources\Incentives\Pages\ListIncentives;
use App\Filament\Resources\Incentives\Schemas\IncentiveForm;
use App\Filament\Resources\Incentives\Tables\IncentivesTable;
use App\Models\Incentive;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IncentiveResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '17. Payroll';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = Incentive::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return IncentiveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncentivesTable::configure($table);
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
            'index' => ListIncentives::route('/'),
            'create' => CreateIncentive::route('/create'),
            'edit' => EditIncentive::route('/{record}/edit'),
        ];
    }
}
