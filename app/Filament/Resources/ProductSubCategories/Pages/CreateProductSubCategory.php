<?php

namespace App\Filament\Resources\ProductSubCategories\Pages;

use App\Filament\Resources\ProductSubCategories\ProductSubCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductSubCategory extends CreateRecord
{
    protected static string $resource = ProductSubCategoryResource::class;
}
