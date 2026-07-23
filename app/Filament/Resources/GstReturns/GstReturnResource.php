<?php

namespace App\Filament\Resources\GstReturns;

use App\Filament\Resources\GstReturns\Pages\CreateGstReturn;
use App\Filament\Resources\GstReturns\Pages\EditGstReturn;
use App\Filament\Resources\GstReturns\Pages\ListGstReturns;
use App\Filament\Resources\GstReturns\Schemas\GstReturnForm;
use App\Filament\Resources\GstReturns\Tables\GstReturnsTable;
use App\Models\GstReturn;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GstReturnResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '15. GST & Taxation';
    protected static ?int $navigationSort = 3;
    protected static ?string $model = GstReturn::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return GstReturnForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GstReturnsTable::configure($table);
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
            'index' => ListGstReturns::route('/'),
            'create' => CreateGstReturn::route('/create'),
            'edit' => EditGstReturn::route('/{record}/edit'),
        ];
    }
}
