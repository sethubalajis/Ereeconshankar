<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class LoyaltyCard extends Model
{
    use HasBranch;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->card_number)) {
                $model->card_number = 'LC-' . strtoupper(\Illuminate\Support\Str::random(10));
            }
        });
    }

    //
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
