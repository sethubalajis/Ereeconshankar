<?php

namespace App\Filament\Branch\Resources\VendorCategories\Pages;

use App\Filament\Branch\Resources\VendorCategories\VendorCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorCategory extends CreateRecord
{
    protected static string $resource = VendorCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
