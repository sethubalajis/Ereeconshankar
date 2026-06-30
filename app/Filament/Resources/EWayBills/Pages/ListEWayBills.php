<?php

namespace App\Filament\Resources\EWayBills\Pages;

use App\Filament\Resources\EWayBills\EWayBillResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEWayBills extends ListRecords
{
    protected static string $resource = EWayBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
