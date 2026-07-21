<?php

namespace App\Filament\Branch\Resources\CustomerFeedback\Pages;

use App\Filament\Branch\Resources\CustomerFeedback\CustomerFeedbackResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerFeedback extends EditRecord
{
    protected static string $resource = CustomerFeedbackResource::class;

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
