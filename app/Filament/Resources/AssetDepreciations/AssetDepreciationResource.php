<?php

namespace App\Filament\Resources\AssetDepreciations;

use App\Filament\Resources\AssetDepreciations\Pages\CreateAssetDepreciation;
use App\Filament\Resources\AssetDepreciations\Pages\EditAssetDepreciation;
use App\Filament\Resources\AssetDepreciations\Pages\ListAssetDepreciations;
use App\Filament\Resources\AssetDepreciations\Schemas\AssetDepreciationForm;
use App\Filament\Resources\AssetDepreciations\Tables\AssetDepreciationsTable;
use App\Models\AssetDepreciation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssetDepreciationResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '18. Asset Management';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = AssetDepreciation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AssetDepreciationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetDepreciationsTable::configure($table);
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
            'index' => ListAssetDepreciations::route('/'),
            'create' => CreateAssetDepreciation::route('/create'),
            'edit' => EditAssetDepreciation::route('/{record}/edit'),
        ];
    }
}
