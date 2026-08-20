<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentNumberSequence extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'branch_id',
        'document_type',
        'financial_year',
        'current_number',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
