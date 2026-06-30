<?php

namespace App\Filament\Resources\BranchExpenses\Pages;

use App\Filament\Resources\BranchExpenses\BranchExpenseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBranchExpense extends EditRecord
{
    protected static string $resource = BranchExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
