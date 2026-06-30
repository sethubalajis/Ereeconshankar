<?php

namespace App\Filament\Resources\BranchSettings\Pages;

use App\Filament\Resources\BranchSettings\BranchSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBranchSetting extends EditRecord
{
    protected static string $resource = BranchSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
