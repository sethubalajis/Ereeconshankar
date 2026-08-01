<?php

namespace App\Models;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class AssetDepreciation extends Model
{
    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function asset() {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
