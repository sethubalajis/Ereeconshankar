<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentNumberConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'document_type',
        'prefix',
        'suffix',
        'include_financial_year',
        'financial_year_format',
        'separator',
        'number_padding',
        'starting_number',
        'reset_frequency',
        'branch_wise',
        'is_active',
    ];

    protected $casts = [
        'include_financial_year' => 'boolean',
        'branch_wise' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
