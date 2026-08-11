<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_stackable' => 'boolean',
        'is_exclusive' => 'boolean',
        'best_price_only' => 'boolean',
        'exclusions' => 'array',
        'eligible_customers' => 'array',
        'eligible_customer_groups' => 'array',
        'eligible_memberships' => 'array',
        'eligible_products' => 'array',
        'eligible_categories' => 'array',
        'eligible_brands' => 'array',
        'eligible_branches' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
