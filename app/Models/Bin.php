<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bin extends Model
{
    protected $guarded = [];

    //
    public function rack() {
        return $this->belongsTo(Rack::class, 'rack_id');
    }
}
