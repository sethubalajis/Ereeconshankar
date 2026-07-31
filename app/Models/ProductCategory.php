<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (self $category): void {
            if (empty($category->code)) {
                $lastCategory = self::orderBy('id', 'desc')->first();
                $nextId = $lastCategory ? $lastCategory->id + 1 : 1;
                $category->code = 'CAT-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
    public function productCategory() {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
