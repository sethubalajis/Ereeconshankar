<?php

namespace App\Filament\Resources\TcsRecords\Pages;

use App\Filament\Resources\TcsRecords\TcsRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTcsRecord extends EditRecord
{
    protected static string $resource = TcsRecordResource::class;

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
