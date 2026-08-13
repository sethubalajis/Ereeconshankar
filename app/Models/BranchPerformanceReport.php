<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class BranchPerformanceReport extends Model
{
    use HasBranch;

    protected $guarded = [];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'report_month' => 'date',
        'score' => 'float',
    ];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
