<?php

namespace App\Filament\Resources\Incentives\Pages;

use App\Filament\Resources\Incentives\IncentiveResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIncentive extends EditRecord
{
    protected static string $resource = IncentiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
