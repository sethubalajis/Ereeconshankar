<?php

namespace App\Filament\Branch\Resources\BranchSettings\Pages;

use App\Filament\Branch\Resources\BranchSettings\BranchSettingResource;
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
