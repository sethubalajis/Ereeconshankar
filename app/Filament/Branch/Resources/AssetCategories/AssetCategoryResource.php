<?php

namespace App\Filament\Branch\Resources\AssetCategories;

use App\Filament\Branch\Resources\AssetCategories\Pages\CreateAssetCategory;
use App\Filament\Branch\Resources\AssetCategories\Pages\EditAssetCategory;
use App\Filament\Branch\Resources\AssetCategories\Pages\ListAssetCategories;
use App\Filament\Branch\Resources\AssetCategories\Schemas\AssetCategoryForm;
use App\Filament\Branch\Resources\AssetCategories\Tables\AssetCategoriesTable;
use App\Models\AssetCategory;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssetCategoryResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '18. Asset Management';
    protected static ?int $navigationSort = 1;
    protected static ?string $model = AssetCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function form(Schema $schema): Schema
    {
        return AssetCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetCategoriesTable::configure($table);
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
            'index' => ListAssetCategories::route('/'),
            'create' => CreateAssetCategory::route('/create'),
            'edit' => EditAssetCategory::route('/{record}/edit'),
        ];
    }
}
