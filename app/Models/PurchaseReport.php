<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'report_date' => 'date',
    ];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
