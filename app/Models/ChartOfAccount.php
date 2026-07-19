<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->account_code)) {
                $maxCode = static::max('account_code');
                $model->account_code = $maxCode ? (string) ((int) $maxCode + 1) : '1001';
            }
        });
    }

    //
    public function accountGroup() {
        return $this->belongsTo(AccountGroup::class, 'account_group_id');
    }
}
