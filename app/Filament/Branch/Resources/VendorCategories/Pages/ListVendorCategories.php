<?php

namespace App\Filament\Branch\Resources\VendorCategories\Pages;

use App\Filament\Branch\Resources\VendorCategories\VendorCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVendorCategories extends ListRecords
{
    protected static string $resource = VendorCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
