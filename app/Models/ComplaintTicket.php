<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class ComplaintTicket extends Model
{
    use HasBranch;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function (self $model): void {
            if (filled($model->ticket_number)) {
                return;
            }

            $year = now()->format('Y');
            $prefix = "TKT-{$year}-";

            $latest = self::query()
                ->where('ticket_number', 'like', $prefix . '%')
                ->select('ticket_number')
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $nextNumber = 1;

            if ($latest?->ticket_number) {
                $lastPart = (int) substr($latest->ticket_number, strlen($prefix));
                $nextNumber = $lastPart + 1;
            }

            $model->ticket_number = $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }

    //
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function complaintCategory() {
        return $this->belongsTo(ComplaintCategory::class, 'complaint_category_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
