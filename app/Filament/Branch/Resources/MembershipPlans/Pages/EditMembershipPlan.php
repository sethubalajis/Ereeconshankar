<?php

namespace App\Filament\Branch\Resources\MembershipPlans\Pages;

use App\Filament\Branch\Resources\MembershipPlans\MembershipPlanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMembershipPlan extends EditRecord
{
    protected static string $resource = MembershipPlanResource::class;

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
