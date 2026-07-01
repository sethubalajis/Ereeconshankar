<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{

    protected $guarded = [];

    public function company() {
        return $this->belongsTo(Company::class);
    }
    protected static function booted()
    {
        static::creating(function ($businessUnit) {
            if (empty($businessUnit->code)) {
                $lastRecord = self::orderBy('id', 'desc')->first();
                $nextId = $lastRecord ? $lastRecord->id + 1 : 1;
                $businessUnit->code = 'BU-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

}
