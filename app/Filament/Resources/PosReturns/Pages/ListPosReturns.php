<?php

namespace App\Filament\Resources\PosReturns\Pages;

use App\Filament\Resources\PosReturns\PosReturnResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPosReturns extends ListRecords
{
    protected static string $resource = PosReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
