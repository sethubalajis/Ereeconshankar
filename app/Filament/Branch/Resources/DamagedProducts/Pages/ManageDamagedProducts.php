<?php

namespace App\Filament\Branch\Resources\DamagedProducts\Pages;

use App\Filament\Branch\Resources\DamagedProducts\DamagedProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDamagedProducts extends ManageRecords
{
    protected static string $resource = DamagedProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
