<?php

namespace App\Filament\Branch\Resources\ComplaintTickets\Pages;

use App\Filament\Branch\Resources\ComplaintTickets\ComplaintTicketResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComplaintTickets extends ListRecords
{
    protected static string $resource = ComplaintTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
