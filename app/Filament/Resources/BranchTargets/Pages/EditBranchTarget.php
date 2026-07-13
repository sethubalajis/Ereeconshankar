<?php

namespace App\Filament\Resources\BranchTargets\Pages;

use App\Filament\Resources\BranchTargets\BranchTargetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBranchTarget extends EditRecord
{
    protected static string $resource = BranchTargetResource::class;

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
