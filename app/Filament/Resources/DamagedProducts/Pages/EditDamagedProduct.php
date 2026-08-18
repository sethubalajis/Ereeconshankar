<?php

namespace App\Filament\Resources\DamagedProducts\Pages;

use App\Filament\Resources\DamagedProducts\DamagedProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDamagedProduct extends EditRecord
{
    protected static string $resource = DamagedProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
