<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrReport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'report_month' => 'date',
    ];
}
