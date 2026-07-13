<?php

namespace App\Filament\Resources\TransferApprovals\Pages;

use App\Filament\Resources\TransferApprovals\TransferApprovalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTransferApproval extends EditRecord
{
    protected static string $resource = TransferApprovalResource::class;

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
