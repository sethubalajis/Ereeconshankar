<?php

namespace App\Filament\Branch\Resources\BranchUsers\Pages;

use App\Filament\Branch\Resources\BranchUsers\BranchUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBranchUser extends CreateRecord
{
    protected static string $resource = BranchUserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
