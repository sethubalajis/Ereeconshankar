<?php

namespace App\Filament\Resources\BranchUsers\Pages;

use App\Filament\Resources\BranchUsers\BranchUserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBranchUser extends EditRecord
{
    protected static string $resource = BranchUserResource::class;

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
