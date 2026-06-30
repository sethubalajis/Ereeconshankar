<?php

namespace App\Filament\Resources\PaymentModes\Pages;

use App\Filament\Resources\PaymentModes\PaymentModeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPaymentMode extends EditRecord
{
    protected static string $resource = PaymentModeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
