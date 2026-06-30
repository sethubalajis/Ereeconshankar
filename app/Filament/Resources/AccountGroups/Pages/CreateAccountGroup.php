<?php

namespace App\Filament\Resources\AccountGroups\Pages;

use App\Filament\Resources\AccountGroups\AccountGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAccountGroup extends CreateRecord
{
    protected static string $resource = AccountGroupResource::class;
}
