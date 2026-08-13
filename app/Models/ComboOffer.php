<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class ComboOffer extends Model
{
    use HasBranch;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = 'CO-' . strtoupper(\Illuminate\Support\Str::random(6));
            }
        });
    }

    //

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
