<?php

namespace App\Filament\Resources\ProductSubCategories\Pages;

use App\Filament\Resources\ProductSubCategories\ProductSubCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductSubCategories extends ListRecords
{
    protected static string $resource = ProductSubCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
