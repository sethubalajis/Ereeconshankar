<?php

namespace App\Filament\Branch\Resources\BranchExpenses\Pages;

use App\Filament\Branch\Resources\BranchExpenses\BranchExpenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBranchExpenses extends ListRecords
{
    protected static string $resource = BranchExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
