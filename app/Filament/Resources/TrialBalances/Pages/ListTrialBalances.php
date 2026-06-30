<?php

namespace App\Filament\Resources\TrialBalances\Pages;

use App\Filament\Resources\TrialBalances\TrialBalanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrialBalances extends ListRecords
{
    protected static string $resource = TrialBalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
