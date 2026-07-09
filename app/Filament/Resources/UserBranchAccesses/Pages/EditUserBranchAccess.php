<?php

namespace App\Filament\Resources\UserBranchAccesses\Pages;

use App\Filament\Resources\UserBranchAccesses\UserBranchAccessResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUserBranchAccess extends EditRecord
{
    protected static string $resource = UserBranchAccessResource::class;

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
