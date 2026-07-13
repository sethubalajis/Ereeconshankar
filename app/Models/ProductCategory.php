<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $guarded = [];

    //
    public function productCategory() {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
