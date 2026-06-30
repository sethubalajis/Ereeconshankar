<?php

namespace App\Filament\Resources\MerchandisePlannings;

use App\Filament\Resources\MerchandisePlannings\Pages\CreateMerchandisePlanning;
use App\Filament\Resources\MerchandisePlannings\Pages\EditMerchandisePlanning;
use App\Filament\Resources\MerchandisePlannings\Pages\ListMerchandisePlannings;
use App\Filament\Resources\MerchandisePlannings\Schemas\MerchandisePlanningForm;
use App\Filament\Resources\MerchandisePlannings\Tables\MerchandisePlanningsTable;
use App\Models\MerchandisePlanning;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MerchandisePlanningResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '4. Merchandise Management';
    protected static ?string $model = MerchandisePlanning::class;
    protected static ?int $navigationSort = 10;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return MerchandisePlanningForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MerchandisePlanningsTable::configure($table);
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
            'index' => ListMerchandisePlannings::route('/'),
            'create' => CreateMerchandisePlanning::route('/create'),
            'edit' => EditMerchandisePlanning::route('/{record}/edit'),
        ];
    }
}
