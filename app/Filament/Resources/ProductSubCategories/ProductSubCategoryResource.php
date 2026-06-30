<?php

namespace App\Filament\Resources\ProductSubCategories;

use App\Filament\Resources\ProductSubCategories\Pages\CreateProductSubCategory;
use App\Filament\Resources\ProductSubCategories\Pages\EditProductSubCategory;
use App\Filament\Resources\ProductSubCategories\Pages\ListProductSubCategories;
use App\Filament\Resources\ProductSubCategories\Schemas\ProductSubCategoryForm;
use App\Filament\Resources\ProductSubCategories\Tables\ProductSubCategoriesTable;
use App\Models\ProductSubCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductSubCategoryResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '3. Product Master';
    protected static ?string $model = ProductSubCategory::class;
    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProductSubCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductSubCategoriesTable::configure($table);
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
            'index' => ListProductSubCategories::route('/'),
            'create' => CreateProductSubCategory::route('/create'),
            'edit' => EditProductSubCategory::route('/{record}/edit'),
        ];
    }
}
