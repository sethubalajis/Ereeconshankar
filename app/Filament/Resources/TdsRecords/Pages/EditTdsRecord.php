<?php

namespace App\Filament\Resources\TdsRecords\Pages;

use App\Filament\Resources\TdsRecords\TdsRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTdsRecord extends EditRecord
{
    protected static string $resource = TdsRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
