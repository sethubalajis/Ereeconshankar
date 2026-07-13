<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTaxDeclaration extends Model
{
    protected $guarded = [];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    public function financialYear() {
        return $this->belongsTo(FinancialYear::class);
    }
    public function items() {
        return $this->hasMany(EmployeeTaxDeclarationItem::class);
    }
    public function company() {
        return $this->belongsTo(Company::class);
    }
    public function branch() {
        return $this->belongsTo(Branch::class);
    }
    public function submittedBy() {
        return $this->belongsTo(User::class, 'submitted_by');
    }
    public function approvedBy() {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
