<?php

namespace App\Filament\Branch\Resources\GiftVouchers\Pages;

use App\Filament\Branch\Resources\GiftVouchers\GiftVoucherResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGiftVouchers extends ListRecords
{
    protected static string $resource = GiftVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
