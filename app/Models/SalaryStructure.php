<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    protected $guarded = [];

    protected $casts = [
        'other_allowances' => 'array',
    ];
}
