<?php

namespace App\Services;

use App\Models\TransferRequest;
use App\Models\TransferApproval;
use App\Models\StockTransfer;
use App\Models\TransferItem;
use App\Models\TransitInventory;
use App\Models\TransferReceipt;
use App\Models\DamagedProduct;
use App\Models\Stock;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Exception;

class StockTransferService
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Approve a Transfer Request
     */
    public function approveRequest(TransferRequest $request, $approvedItems, $userId, $remarks = null)
    {
        DB::beginTransaction();

        try {
            if ($request->status !== 'PENDING_APPROVAL' && $request->status !== 'pending' && $request->status !== 'DRAFT') {
                throw new Exception("Transfer request cannot be approved in its current state.");
            }

            // Create Transfer Approval
            $approval = TransferApproval::create([
                'transfer_request_id' => $request->id,
                'approver_id' => $userId,
                'approval_date' => now(),
                'status' => 'APPROVED',
                'remarks' => $remarks
            ]);

            // Create Stock Transfer
            $transfer = StockTransfer::create([
                'transfer_number' => 'ST-' . date('Ymd') . '-' . str_pad($request->id, 4, '0', STR_PAD_LEFT),
                'transfer_request_id' => $request->id,
                'from_branch_id' => $request->from_branch_id,
                'to_branch_id' => $request->to_branch_id,
                'transfer_date' => now(),
                'created_by' => $request->requested_by,
                'approved_by' => $userId,
                'status' => 'APPROVED',
                'financial_year_id' => $request->financial_year_id,
            ]);

            // Create Transfer Items (assuming $approvedItems is array of arrays)
            foreach ($approvedItems as $itemData) {
                TransferItem::create([
                    'stock_transfer_id' => $transfer->id,
                    'product_id' => $itemData['product_id'],
                    'product_variant_id' => $itemData['product_variant_id'] ?? null,
                    'requested_quantity' => $itemData['requested_quantity'] ?? 0,
                    'approved_quantity' => $itemData['approved_quantity'] ?? 0,
                    'quantity' => $itemData['approved_quantity'] ?? 0, // Fallback for old code
                    'unit_cost' => $itemData['unit_cost'] ?? 0,
                    'remarks' => $itemData['remarks'] ?? null,
                ]);
                $approval->increment('approved_quantity', $itemData['approved_quantity'] ?? 0);
            }

            $request->update(['status' => 'APPROVED']);

            DB::commit();
            return $transfer;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Dispatch a Stock Transfer
     */
    public function dispatchTransfer(StockTransfer $transfer, $dispatchItems, $userId, $vehicleNumber = null, $driverName = null)
    {
        DB::beginTransaction();

        try {
            if ($transfer->status !== 'APPROVED' && $transfer->status !== 'PREPARING') {
                throw new Exception("Transfer cannot be dispatched in its current state.");
            }

            foreach ($dispatchItems as $itemId => $qty) {
                $item = TransferItem::findOrFail($itemId);
                
                if ($qty <= 0) continue;
                if ($qty > $item->approved_quantity) {
                    throw new Exception("Dispatched quantity cannot exceed approved quantity.");
                }

                // Check source stock
                $stock = Stock::where([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'branch_id' => $transfer->from_branch_id,
                ])->first();

                $available = $stock ? ($stock->quantity - ($stock->reserved_qty ?? 0) - ($stock->damaged_qty ?? 0)) : 0;
                
                if ($available < $qty) {
                    throw new Exception("Insufficient stock for product ID: {$item->product_id}");
                }

                // 1. Update Source Stock and create Stock Transaction (OUT)
                $this->inventoryService->updateStock(
                    $item->product_id,
                    $item->product_variant_id,
                    $transfer->from_branch_id,
                    $transfer->source_warehouse_id,
                    'out',
                    $qty,
                    $item->unit_cost,
                    $transfer->transfer_number,
                    'STOCK_TRANSFER',
                    false, // preventNewStockOnOut
                    false  // reuseExistingStockRow
                );

                // 2. Update Transfer Item
                $item->update(['dispatched_quantity' => $qty]);

                // 3. Create Transit Inventory
                TransitInventory::create([
                    'stock_transfer_id' => $transfer->id,
                    'transfer_item_id' => $item->id,
                    'from_branch_id' => $transfer->from_branch_id,
                    'to_branch_id' => $transfer->to_branch_id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'dispatched_quantity' => $qty,
                    'transit_quantity' => $qty,
                    'received_quantity' => 0,
                    'status' => 'IN_TRANSIT',
                    'dispatched_at' => now(),
                ]);
            }

            // Update Transfer Status
            $transfer->update([
                'status' => 'DISPATCHED',
                'dispatched_at' => now(),
                'vehicle_number' => $vehicleNumber,
                'driver_name' => $driverName,
            ]);

            DB::commit();
            return $transfer;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Receive a Stock Transfer
     */
    public function receiveTransfer(StockTransfer $transfer, $receivedItems, $userId, $remarks = null)
    {
        DB::beginTransaction();

        try {
            if ($transfer->status !== 'DISPATCHED' && $transfer->status !== 'PARTIALLY_RECEIVED' && $transfer->status !== 'in_transit') {
                throw new Exception("Transfer cannot be received in its current state.");
            }

            $receipt = TransferReceipt::create([
                'stock_transfer_id' => $transfer->id,
                'receipt_number' => 'TRC-' . date('Ymd') . '-' . str_pad($transfer->id, 4, '0', STR_PAD_LEFT),
                'destination_branch_id' => $transfer->to_branch_id,
                'receive_date' => now(),
                'received_by' => $userId,
                'status' => 'RECEIVED',
                'remarks' => $remarks
            ]);

            $allReceived = true;

            foreach ($receivedItems as $itemId => $data) {
                $item = TransferItem::findOrFail($itemId);
                
                $receivedQty = $data['received_quantity'] ?? 0;
                $damagedQty = $data['damaged_quantity'] ?? 0;
                $acceptedQty = $receivedQty - $damagedQty;

                if ($receivedQty <= 0) continue;

                $transit = TransitInventory::where('transfer_item_id', $item->id)->first();
                if (!$transit) continue;

                if ($receivedQty > $transit->transit_quantity) {
                    throw new Exception("Received quantity cannot exceed transit quantity.");
                }

                // 1. Update Destination Stock and create Stock Transaction (IN)
                if ($acceptedQty > 0) {
                    $this->inventoryService->updateStock(
                        $item->product_id,
                        $item->product_variant_id,
                        $transfer->to_branch_id,
                        $transfer->destination_warehouse_id,
                        'in',
                        $acceptedQty,
                        $item->unit_cost,
                        $transfer->transfer_number,
                        'STOCK_TRANSFER'
                    );
                }

                // 2. Handle Damaged Stock
                if ($damagedQty > 0) {
                    DamagedProduct::create([
                        'branch_id' => $transfer->to_branch_id,
                        'product_id' => $item->product_id,
                        'product_variant_id' => $item->product_variant_id,
                        'quantity' => $damagedQty,
                        'reported_date' => now(),
                        'reason' => 'Damaged during transit',
                        'source_type' => 'App\Models\StockTransfer',
                        'source_id' => $transfer->id,
                    ]);
                }

                // 3. Update Transfer Item
                $newReceivedTotal = $item->received_quantity + $receivedQty;
                $newDamagedTotal = $item->damaged_quantity + $damagedQty;
                $item->update([
                    'received_quantity' => $newReceivedTotal,
                    'damaged_quantity' => $newDamagedTotal,
                ]);

                // 4. Update Transit Inventory
                $newTransitQty = $transit->transit_quantity - $receivedQty;
                $transit->update([
                    'received_quantity' => $transit->received_quantity + $receivedQty,
                    'transit_quantity' => $newTransitQty,
                    'status' => $newTransitQty > 0 ? 'PARTIALLY_RECEIVED' : 'RECEIVED',
                    'received_at' => now(),
                ]);

                if ($newTransitQty > 0) {
                    $allReceived = false;
                }
            }

            // Check if all transit inventories for this transfer are completed
            $remainingTransit = TransitInventory::where('stock_transfer_id', $transfer->id)
                ->where('transit_quantity', '>', 0)
                ->exists();

            $transfer->update([
                'status' => $remainingTransit ? 'PARTIALLY_RECEIVED' : 'COMPLETED',
                'received_at' => now(),
            ]);

            DB::commit();
            return $receipt;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
