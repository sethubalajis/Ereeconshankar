<?php

namespace App\Filament\Resources\VendorPayments\Schemas;

use App\Models\PaymentMode;
use App\Models\PurchaseOrder;
use App\Models\VendorPayment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class VendorPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vendor_id')->relationship('vendor', 'name')
                    ->live()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('purchase_order_id', null);
                        $set('amount', 0);
                    })
                    ->required(),
                Select::make('purchase_order_id')
                    ->label('Purchase order')
                    ->live()
                    ->options(function (Get $get): array {
                        $vendorId = $get('vendor_id');

                        if (! $vendorId) {
                            return [];
                        }

                        return PurchaseOrder::query()
                            ->where('vendor_id', $vendorId)
                            ->orderByDesc('po_date')
                            ->pluck('po_number', 'id')
                            ->all();
                    })
                    ->afterStateUpdated(function (Set $set, Get $get, mixed $state): void {
                        $set('amount', self::remainingAmount(
                            $state ? (int) $state : null,
                            $get('id') ? (int) $get('id') : null,
                        ));
                    })
                    ->searchable()
                    ->preload()
                    ->disabled(fn (Get $get): bool => ! (bool) $get('vendor_id')),
                DatePicker::make('payment_date')
<<<<<<< HEAD
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
=======
                    ->default(now())
                    ->required(),
>>>>>>> origin/main
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->helperText(function (Get $get): string {
                        $remaining = self::remainingAmount(
                            $get('purchase_order_id') ? (int) $get('purchase_order_id') : null,
                            $get('id') ? (int) $get('id') : null,
                        );

                        return 'Remaining PO amount: ₹' . number_format($remaining, 2);
                    }),
                Select::make('payment_mode_id')
                    ->label('Payment mode')
                    ->options(fn (): array => PaymentMode::query()->where('is_active', true)->orderBy('name')->pluck('name', 'id')->all())
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('transaction_reference'),
<<<<<<< HEAD
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ])
=======
                Select::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->searchable()
                    ->native(false)
>>>>>>> origin/main
                    ->required()
                    ->default('completed'),
            ]);
    }

    private static function remainingAmount(?int $purchaseOrderId, ?int $excludePaymentId = null): float
    {
        if (! $purchaseOrderId) {
            return 0.0;
        }

        $totalAmount = (float) (PurchaseOrder::query()->whereKey($purchaseOrderId)->value('total_amount') ?? 0);

        $paidQuery = VendorPayment::query()
            ->where('purchase_order_id', $purchaseOrderId)
            ->whereNotIn('status', ['cancelled', 'failed']);

        if ($excludePaymentId) {
            $paidQuery->whereKeyNot($excludePaymentId);
        }

        $paidAmount = (float) $paidQuery->sum('amount');

        return round(max($totalAmount - $paidAmount, 0), 2);
    }
}
