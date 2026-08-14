<?php

namespace App\Filament\Branch\Resources\ComplaintCategories;

use App\Filament\Branch\Resources\ComplaintCategories\Pages\CreateComplaintCategory;
use App\Filament\Branch\Resources\ComplaintCategories\Pages\EditComplaintCategory;
use App\Filament\Branch\Resources\ComplaintCategories\Pages\ListComplaintCategories;
use App\Filament\Branch\Resources\ComplaintCategories\Schemas\ComplaintCategoryForm;
use App\Filament\Branch\Resources\ComplaintCategories\Tables\ComplaintCategoriesTable;
use App\Models\ComplaintCategory;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ComplaintCategoryResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '19. Service & Complaints';
    protected static ?int $navigationSort = 1;
    protected static ?string $model = ComplaintCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function form(Schema $schema): Schema
    {
        return ComplaintCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComplaintCategoriesTable::configure($table);
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
            'index' => ListComplaintCategories::route('/'),
            'create' => CreateComplaintCategory::route('/create'),
            'edit' => EditComplaintCategory::route('/{record}/edit'),
        ];
    }
}
