<?php

namespace App\Filament\Resources\ProductVariants\Pages;

use App\Filament\Resources\ProductVariants\ProductVariantResource;
use App\Filament\Resources\ProductVariants\Schemas\ProductVariantForm;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductVariant extends EditRecord
{
    protected static string $resource = ProductVariantResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return array_merge($data, ProductVariantForm::prepareAttributeInputsForRecord($this->record));
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['attribute_inputs']);

        return $data;
    }

    protected function afterSave(): void
    {
        $state = $this->form->getState();
        ProductVariantForm::syncAttributeInputs($this->record, (array) ($state['attribute_inputs'] ?? []));
        ProductVariantForm::syncBarcodeRecord($this->record);
    }

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
