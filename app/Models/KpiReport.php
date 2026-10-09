<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiReport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'report_date' => 'date',
        'value' => 'float',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->report_date) && !empty($model->from_date)) {
                $model->report_date = $model->from_date;
            }
        });
    }
}