<?php

namespace App\Filament\Resources\GiftVouchers\Pages;

use App\Filament\Resources\GiftVouchers\GiftVoucherResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGiftVoucher extends CreateRecord
{
    protected static string $resource = GiftVoucherResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
