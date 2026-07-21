<?php

namespace App\Filament\Branch\Resources\Stocks\Pages;

use App\Filament\Branch\Resources\Stocks\StockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStock extends CreateRecord
{
    protected static string $resource = StockResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $stock = static::getModel()::where('product_id', $data['product_id'])
            ->where('product_variant_id', $data['product_variant_id'] ?? null)
            ->where('branch_id', $data['branch_id'])
            ->where('warehouse_id', $data['warehouse_id'] ?? null)
            ->first();

        if ($stock) {
            $stock->update([
                'quantity' => $data['quantity'],
                'avg_cost' => $data['avg_cost'] ?? 0,
            ]);
            return $stock;
        }

        return static::getModel()::create($data);
    }
}
