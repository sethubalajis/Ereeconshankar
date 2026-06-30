<?php

namespace App\Filament\Resources\GstCategories\Pages;

use App\Filament\Resources\GstCategories\GstCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGstCategories extends ListRecords
{
    protected static string $resource = GstCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
