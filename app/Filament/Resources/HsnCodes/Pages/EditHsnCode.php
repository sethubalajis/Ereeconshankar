<?php

namespace App\Filament\Resources\HsnCodes\Pages;

use App\Filament\Resources\HsnCodes\HsnCodeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHsnCode extends EditRecord
{
    protected static string $resource = HsnCodeResource::class;

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
