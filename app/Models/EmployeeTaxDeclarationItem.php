<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTaxDeclarationItem extends Model
{
    protected $guarded = [];

    public function declaration() {
        return $this->belongsTo(EmployeeTaxDeclaration::class, 'employee_tax_declaration_id');
    }
    public function investmentType() {
        return $this->belongsTo(InvestmentType::class);
    }
}
