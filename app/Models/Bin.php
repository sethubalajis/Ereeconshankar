<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bin extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (self $bin): void {
            if (empty($bin->code)) {
                $lastBin = self::orderBy('id', 'desc')->first();
                $nextId = $lastBin ? $lastBin->id + 1 : 1;
                $bin->code = 'BIN-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
    public function rack() {
        return $this->belongsTo(Rack::class, 'rack_id');
    }
}
