<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionAudit extends Model
{
    use HasBranch;

    protected $guarded = [];
    /** @use HasFactory<\Database\Factories\PromotionAuditFactory> */
    use HasFactory;

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
