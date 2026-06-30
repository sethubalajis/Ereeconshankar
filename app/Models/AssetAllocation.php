<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetAllocation extends Model
{
    protected $guarded = [];

    //
    public function asset() {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
