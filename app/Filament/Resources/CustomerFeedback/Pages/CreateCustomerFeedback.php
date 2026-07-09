<?php

namespace App\Filament\Resources\CustomerFeedback\Pages;

use App\Filament\Resources\CustomerFeedback\CustomerFeedbackResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerFeedback extends CreateRecord
{
    protected static string $resource = CustomerFeedbackResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
