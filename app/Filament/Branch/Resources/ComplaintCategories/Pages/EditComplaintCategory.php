<?php

namespace App\Filament\Branch\Resources\ComplaintCategories\Pages;

use App\Filament\Branch\Resources\ComplaintCategories\ComplaintCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComplaintCategory extends EditRecord
{
    protected static string $resource = ComplaintCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
