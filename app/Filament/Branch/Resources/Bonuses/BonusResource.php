<?php

namespace App\Filament\Branch\Resources\Bonuses;

use App\Filament\Branch\Resources\Bonuses\Pages\CreateBonus;
use App\Filament\Branch\Resources\Bonuses\Pages\EditBonus;
use App\Filament\Branch\Resources\Bonuses\Pages\ListBonuses;
use App\Filament\Branch\Resources\Bonuses\Schemas\BonusForm;
use App\Filament\Branch\Resources\Bonuses\Tables\BonusesTable;
use App\Models\Bonus;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BonusResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '17. Payroll';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = Bonus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return BonusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BonusesTable::configure($table);
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
            'index' => ListBonuses::route('/'),
            'create' => CreateBonus::route('/create'),
            'edit' => EditBonus::route('/{record}/edit'),
        ];
    }
}
