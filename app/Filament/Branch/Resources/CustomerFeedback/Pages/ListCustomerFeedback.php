<?php

namespace App\Filament\Branch\Resources\CustomerFeedback\Pages;

use App\Filament\Branch\Resources\CustomerFeedback\CustomerFeedbackResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerFeedback extends ListRecords
{
    protected static string $resource = CustomerFeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
