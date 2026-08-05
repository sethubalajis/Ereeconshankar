<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Color extends Model
{
    protected $guarded = [];

    public function productVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'color_id');
    }
}
