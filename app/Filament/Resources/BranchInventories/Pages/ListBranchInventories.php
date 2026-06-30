<?php

namespace App\Filament\Resources\BranchInventories\Pages;

use App\Filament\Resources\BranchInventories\BranchInventoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBranchInventories extends ListRecords
{
    protected static string $resource = BranchInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
