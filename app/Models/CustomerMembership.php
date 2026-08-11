<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerMembership extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->membership_number)) {
                $model->membership_number = 'CM-' . strtoupper(\Illuminate\Support\Str::random(6));
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function membershipPlan()
    {
        return $this->belongsTo(MembershipPlan::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
