<?php

namespace App\Filament\Resources\PosReturns\Pages;

use App\Filament\Resources\PosReturns\PosReturnResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPosReturn extends EditRecord
{
    protected static string $resource = PosReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
