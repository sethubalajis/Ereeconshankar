<?php

namespace App\Filament\Branch\Resources\Deductions\Pages;

use App\Filament\Branch\Resources\Deductions\DeductionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDeduction extends CreateRecord
{
    protected static string $resource = DeductionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
