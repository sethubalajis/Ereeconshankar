<?php

namespace App\Filament\Resources\ComplaintTickets\Pages;

use App\Filament\Resources\ComplaintTickets\ComplaintTicketResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComplaintTicket extends EditRecord
{
    protected static string $resource = ComplaintTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
