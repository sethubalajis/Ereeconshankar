<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'as_of_date' => 'date',
    ];
}
