<?php

namespace App\Filament\Branch\Resources\PosSales\Pages;

use App\Filament\Branch\Resources\PosSales\PosSaleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPosSales extends ListRecords
{
    protected static string $resource = PosSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
