<?php

namespace App\Filament\Resources\ProductVariants\Pages;

use App\Filament\Resources\ProductVariants\ProductVariantResource;
use App\Filament\Resources\ProductVariants\Schemas\ProductVariantForm;
use Filament\Resources\Pages\CreateRecord;

class CreateProductVariant extends CreateRecord
{
    protected static string $resource = ProductVariantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['attribute_inputs']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $state = $this->form->getState();
        ProductVariantForm::syncAttributeInputs($this->record, (array) ($state['attribute_inputs'] ?? []));
        ProductVariantForm::syncBarcodeRecord($this->record);
    }
}
