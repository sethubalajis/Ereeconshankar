<?php

namespace App\Filament\Branch\Resources\PosReturns\Pages;

use App\Filament\Branch\Resources\PosReturns\PosReturnResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePosReturn extends CreateRecord
{
    protected static string $resource = PosReturnResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
                    'return_reason' => $item['return_reason'] ?? 'Exchange Request',
                    'condition' => $item['condition'] ?? 'Good',
                ];
            })
            ->filter(fn ($item): bool => (float) $item['return_quantity'] > 0)
            ->values();

        $data['return_items'] = $items->all();
        $data['return_amount'] = round((float) $items->sum('line_total'), 2);

        if (!empty($data['pos_sale_id'])) {
            $sale = \App\Models\PosSale::find($data['pos_sale_id']);
            if ($sale) {
                $data['branch_id'] = $sale->branch_id;
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
