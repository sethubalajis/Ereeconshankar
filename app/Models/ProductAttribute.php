<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAttribute extends Model
{
    protected $guarded = [];

    public function attributeValues(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'product_attribute_id');
    }
}
