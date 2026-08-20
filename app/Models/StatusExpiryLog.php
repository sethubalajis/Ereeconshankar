<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusExpiryLog extends Model
{
    protected $guarded = [];

    public function model()
    {
        return $this->morphTo();
    }
}
