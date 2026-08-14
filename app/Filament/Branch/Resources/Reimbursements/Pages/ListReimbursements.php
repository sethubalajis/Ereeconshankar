<?php

namespace App\Filament\Branch\Resources\Reimbursements\Pages;

use App\Filament\Branch\Resources\Reimbursements\ReimbursementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReimbursements extends ListRecords
{
    protected static string $resource = ReimbursementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
