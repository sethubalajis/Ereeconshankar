<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->quotation_number)) {
                $latest = static::latest('id')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $model->quotation_number = 'QUO-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function rfq() {
        return $this->belongsTo(Rfq::class, 'rfq_id');
    }
    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
