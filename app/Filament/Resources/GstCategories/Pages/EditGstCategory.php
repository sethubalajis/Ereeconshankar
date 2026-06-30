<?php

namespace App\Filament\Resources\GstCategories\Pages;

use App\Filament\Resources\GstCategories\GstCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGstCategory extends EditRecord
{
    protected static string $resource = GstCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
