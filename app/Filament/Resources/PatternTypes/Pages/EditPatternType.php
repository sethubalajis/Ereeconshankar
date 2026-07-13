<?php

namespace App\Filament\Resources\PatternTypes\Pages;

use App\Filament\Resources\PatternTypes\PatternTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPatternType extends EditRecord
{
    protected static string $resource = PatternTypeResource::class;

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
