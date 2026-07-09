<?php

namespace App\Filament\Resources\TransferApprovals\Pages;

use App\Filament\Resources\TransferApprovals\TransferApprovalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransferApproval extends CreateRecord
{
    protected static string $resource = TransferApprovalResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
