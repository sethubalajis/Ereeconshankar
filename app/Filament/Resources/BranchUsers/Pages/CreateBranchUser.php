<?php

namespace App\Filament\Resources\BranchUsers\Pages;

use App\Filament\Resources\BranchUsers\BranchUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBranchUser extends CreateRecord
{
    protected static string $resource = BranchUserResource::class;
}
