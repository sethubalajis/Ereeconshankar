<?php

namespace App\Filament\Resources\PickingLists\Pages;

use App\Filament\Resources\PickingLists\PickingListResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPickingList extends EditRecord
{
    protected static string $resource = PickingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
