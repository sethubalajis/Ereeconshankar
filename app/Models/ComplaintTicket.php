<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintTicket extends Model
{
    protected $guarded = [];

    //
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function complaintCategory() {
        return $this->belongsTo(ComplaintCategory::class, 'complaint_category_id');
    }
}
