<?php

namespace App\Filament\Branch\Resources\TransferApprovals\Pages;

use App\Filament\Branch\Resources\TransferApprovals\TransferApprovalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransferApprovals extends ListRecords
{
    protected static string $resource = TransferApprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
