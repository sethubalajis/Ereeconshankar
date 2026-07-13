<?php

namespace App\Filament\Resources\PosTerminals\Pages;

use App\Filament\Resources\PosTerminals\PosTerminalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPosTerminal extends EditRecord
{
    protected static string $resource = PosTerminalResource::class;

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
