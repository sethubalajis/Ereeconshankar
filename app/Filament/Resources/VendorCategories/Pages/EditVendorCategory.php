<?php

namespace App\Filament\Resources\VendorCategories\Pages;

use App\Filament\Resources\VendorCategories\VendorCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVendorCategory extends EditRecord
{
    protected static string $resource = VendorCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
