<?php

namespace App\Filament\Resources\GstReturns\Pages;

use App\Filament\Resources\GstReturns\GstReturnResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGstReturns extends ListRecords
{
    protected static string $resource = GstReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
