<?php

namespace App\Filament\Branch\Resources\MembershipPlans\Pages;

use App\Filament\Branch\Resources\MembershipPlans\MembershipPlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMembershipPlan extends CreateRecord
{
    protected static string $resource = MembershipPlanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
