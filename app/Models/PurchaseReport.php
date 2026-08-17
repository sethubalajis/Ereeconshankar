<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class PurchaseReport extends Model
{
    use HasBranch;

    protected $guarded = [];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'report_date' => 'date',
    ];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($report) {
            if ($report->branch_id && ($report->from_date || $report->to_date || $report->report_date)) {
                $query = \App\Models\PurchaseOrder::query();

                if ($report->from_date && $report->to_date) {
                    $query->whereBetween('po_date', [$report->from_date, $report->to_date]);
                } elseif ($report->from_date) {
                    $query->whereDate('po_date', '>=', $report->from_date);
                } elseif ($report->to_date) {
                    $query->whereDate('po_date', '<=', $report->to_date);
                } elseif ($report->report_date) {
                    // It's cast as date, so it's a Carbon instance
                    $query->whereDate('po_date', $report->report_date);
                }

                if (\Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\PurchaseOrder)->getTable(), 'branch_id')) {
                    $query->where('branch_id', $report->branch_id);
                }

                $report->total_purchases = $query->sum('total_amount');
            }
        });
    }
}
