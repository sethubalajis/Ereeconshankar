<?php

namespace App\Models;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class TaxInvoice extends Model
{
    use BelongsToFinancialYear;
    protected $guarded = [];

    //
}
