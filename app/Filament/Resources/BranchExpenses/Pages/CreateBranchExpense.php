<?php

namespace App\Filament\Resources\BranchExpenses\Pages;

use App\Filament\Resources\BranchExpenses\BranchExpenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBranchExpense extends CreateRecord
{
    protected static string $resource = BranchExpenseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
