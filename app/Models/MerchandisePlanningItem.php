<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchandisePlanningItem extends Model
{
    protected $guarded = [];

    public function merchandisePlanning(): BelongsTo
    {
        return $this->belongsTo(MerchandisePlanning::class, 'merchandise_planning_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function design(): BelongsTo
    {
        return $this->belongsTo(Design::class, 'design_id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    public function fabricType(): BelongsTo
    {
        return $this->belongsTo(FabricType::class, 'fabric_type_id');
    }

    public function patternType(): BelongsTo
    {
        return $this->belongsTo(PatternType::class, 'pattern_type_id');
    }
}
