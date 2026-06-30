<?php

namespace App\Filament\Resources\BranchUsers\Pages;

use App\Filament\Resources\BranchUsers\BranchUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBranchUsers extends ListRecords
{
    protected static string $resource = BranchUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
