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
}
