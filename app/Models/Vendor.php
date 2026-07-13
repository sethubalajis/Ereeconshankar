<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $guarded = [];

<<<<<<< HEAD
    public function vendorCategory()
    {
        return $this->belongsTo(VendorCategory::class);
=======
    //
    public function vendorCategory()
    {
        return $this->belongsTo(VendorCategory::class, 'vendor_category_id');
>>>>>>> origin/main
    }
}
