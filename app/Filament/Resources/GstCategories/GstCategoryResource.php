<?php

namespace App\Filament\Resources\GstCategories;

use App\Filament\Resources\GstCategories\Pages\CreateGstCategory;
use App\Filament\Resources\GstCategories\Pages\EditGstCategory;
use App\Filament\Resources\GstCategories\Pages\ListGstCategories;
use App\Filament\Resources\GstCategories\Schemas\GstCategoryForm;
use App\Filament\Resources\GstCategories\Tables\GstCategoriesTable;
use App\Models\GstCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GstCategoryResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '15. GST & Taxation';
    protected static ?int $navigationSort = 2;
    protected static ?string $model = GstCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function form(Schema $schema): Schema
    {
        return GstCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GstCategoriesTable::configure($table);
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
            'index' => ListGstCategories::route('/'),
            'create' => CreateGstCategory::route('/create'),
            'edit' => EditGstCategory::route('/{record}/edit'),
        ];
    }
}
