<?php

namespace App\Filament\Resources\PaymentModes\Pages;

use App\Filament\Resources\PaymentModes\PaymentModeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaymentModes extends ListRecords
{
    protected static string $resource = PaymentModeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
