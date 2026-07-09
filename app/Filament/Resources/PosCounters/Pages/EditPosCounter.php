<?php

namespace App\Filament\Resources\PosCounters\Pages;

use App\Filament\Resources\PosCounters\PosCounterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPosCounter extends EditRecord
{
    protected static string $resource = PosCounterResource::class;

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
