<?php

namespace App\Filament\Resources\PosCounters\Pages;

use App\Filament\Resources\PosCounters\PosCounterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPosCounters extends ListRecords
{
    protected static string $resource = PosCounterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
