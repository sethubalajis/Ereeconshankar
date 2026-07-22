<?php

namespace App\Filament\Branch\Resources\Grns\Pages;

use App\Filament\Branch\Resources\Grns\GrnResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGrns extends ListRecords
{
    protected static string $resource = GrnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
