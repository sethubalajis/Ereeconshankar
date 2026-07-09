<?php

namespace App\Filament\Resources\PfContributions\Pages;

use App\Filament\Resources\PfContributions\PfContributionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPfContribution extends EditRecord
{
    protected static string $resource = PfContributionResource::class;

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
