<?php

namespace App\Filament\Resources\TrialBalances\Pages;

use App\Filament\Resources\TrialBalances\TrialBalanceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrialBalance extends EditRecord
{
    protected static string $resource = TrialBalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
