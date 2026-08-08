<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionAudit extends Model
{
    protected $guarded = [];
    /** @use HasFactory<\Database\Factories\PromotionAuditFactory> */
    use HasFactory;
}
