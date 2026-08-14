<?php

namespace App\Filament\Branch\Resources\CashClosings\Pages;

use App\Filament\Branch\Resources\CashClosings\CashClosingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCashClosings extends ListRecords
{
    protected static string $resource = CashClosingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
