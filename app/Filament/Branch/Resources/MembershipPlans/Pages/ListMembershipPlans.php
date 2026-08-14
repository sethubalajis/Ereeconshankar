<?php

namespace App\Filament\Branch\Resources\MembershipPlans\Pages;

use App\Filament\Branch\Resources\MembershipPlans\MembershipPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMembershipPlans extends ListRecords
{
    protected static string $resource = MembershipPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
