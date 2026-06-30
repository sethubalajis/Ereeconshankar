<?php

namespace App\Filament\Resources\LoyaltyPrograms\Pages;

use App\Filament\Resources\LoyaltyPrograms\LoyaltyProgramResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoyaltyProgram extends EditRecord
{
    protected static string $resource = LoyaltyProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
