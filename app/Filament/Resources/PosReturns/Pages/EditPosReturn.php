<?php

namespace App\Filament\Resources\PosReturns\Pages;

use App\Filament\Resources\PosReturns\PosReturnResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPosReturn extends EditRecord
{
    protected static string $resource = PosReturnResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $items = collect($data['return_items'] ?? [])
            ->map(function ($item): array {
                $soldQty = (float) ($item['sold_quantity'] ?? 0);
                $qty = max(0, min((float) ($item['return_quantity'] ?? 0), $soldQty));
                $unitPrice = (float) ($item['unit_price'] ?? 0);

                return [
                    'pos_sale_item_id' => $item['pos_sale_item_id'] ?? null,
                    'product_name' => $item['product_name'] ?? null,
                    'variant_sku' => $item['variant_sku'] ?? null,
                    'sold_quantity' => $soldQty,
                    'return_quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'line_total' => round($qty * $unitPrice, 2),
                ];
            })
            ->filter(fn ($item): bool => (float) $item['return_quantity'] > 0)
            ->values();

        $data['return_items'] = $items->all();
        $data['return_amount'] = round((float) $items->sum('line_total'), 2);

        return $data;
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
