<?php

namespace App\Filament\Resources\Grns;

use App\Filament\Resources\Grns\Pages\CreateGrn;
use App\Filament\Resources\Grns\Pages\EditGrn;
use App\Filament\Resources\Grns\Pages\ListGrns;
use App\Filament\Resources\Grns\Schemas\GrnForm;
use App\Filament\Resources\Grns\Tables\GrnsTable;
use App\Models\Grn;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GrnResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '5. Procurement';
    protected static ?string $model = Grn::class;
    protected static ?int $navigationSort = 6;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function form(Schema $schema): Schema
    {
        return GrnForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GrnsTable::configure($table);
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
            'index' => ListGrns::route('/'),
            'create' => CreateGrn::route('/create'),
            'edit' => EditGrn::route('/{record}/edit'),
        ];
    }
}
