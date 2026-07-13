<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    protected $guarded = [];

<<<<<<< HEAD
    public function vendors()
    {
        return $this->hasMany(Vendor::class);
=======
    //
    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'vendor_category_id');
>>>>>>> origin/main
    }
}
