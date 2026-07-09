<?php

namespace App\Filament\Resources\UserBranchAccesses\Pages;

use App\Filament\Resources\UserBranchAccesses\UserBranchAccessResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserBranchAccess extends CreateRecord
{
    protected static string $resource = UserBranchAccessResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
