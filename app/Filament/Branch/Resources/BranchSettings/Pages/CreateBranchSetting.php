<?php

namespace App\Filament\Branch\Resources\BranchSettings\Pages;

use App\Filament\Branch\Resources\BranchSettings\BranchSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBranchSetting extends CreateRecord
{
    protected static string $resource = BranchSettingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
