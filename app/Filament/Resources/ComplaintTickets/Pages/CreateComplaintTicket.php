<?php

namespace App\Filament\Resources\ComplaintTickets\Pages;

use App\Filament\Resources\ComplaintTickets\ComplaintTicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateComplaintTicket extends CreateRecord
{
    protected static string $resource = ComplaintTicketResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
