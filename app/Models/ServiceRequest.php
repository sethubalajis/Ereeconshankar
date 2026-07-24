<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function (self $model): void {
            if (filled($model->request_number)) {
                return;
            }

            $year = now()->format('Y');
            $prefix = "SR-{$year}-";

            $latest = self::query()
                ->where('request_number', 'like', $prefix . '%')
                ->select('request_number')
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $nextNumber = 1;

            if ($latest?->request_number) {
                $lastPart = (int) substr($latest->request_number, strlen($prefix));
                $nextNumber = $lastPart + 1;
            }

            $model->request_number = $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }

    //
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
