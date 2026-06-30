<?php

namespace App\Filament\Resources\PaymentModes\Pages;

use App\Filament\Resources\PaymentModes\PaymentModeResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentMode extends CreateRecord
{
    protected static string $resource = PaymentModeResource::class;
}
