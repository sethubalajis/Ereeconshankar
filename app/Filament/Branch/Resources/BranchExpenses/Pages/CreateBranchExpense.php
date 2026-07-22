<?php

namespace App\Filament\Branch\Resources\BranchExpenses\Pages;

use App\Filament\Branch\Resources\BranchExpenses\BranchExpenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBranchExpense extends CreateRecord
{
    protected static string $resource = BranchExpenseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
