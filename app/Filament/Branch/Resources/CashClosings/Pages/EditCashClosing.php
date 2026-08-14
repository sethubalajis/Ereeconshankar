<?php

namespace App\Filament\Branch\Resources\CashClosings\Pages;

use App\Filament\Branch\Resources\CashClosings\CashClosingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCashClosing extends EditRecord
{
    protected static string $resource = CashClosingResource::class;

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
