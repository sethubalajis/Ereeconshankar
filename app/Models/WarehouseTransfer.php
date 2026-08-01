<?php

namespace App\Models;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class WarehouseTransfer extends Model
{
    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function fromWarehouse() {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }
    public function toWarehouse() {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }
}
