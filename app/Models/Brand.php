<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (self $brand): void {
            if (empty($brand->code)) {
                $lastBrand = self::orderBy('id', 'desc')->first();
                $nextId = $lastBrand ? $lastBrand->id + 1 : 1;
                $brand->code = 'BRD-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
}
