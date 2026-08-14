<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class Bin extends Model
{
    use HasBranch;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (self $bin): void {
            if (empty($bin->code)) {
                $lastBin = self::orderBy('id', 'desc')->first();
                $nextId = $lastBin ? $lastBin->id + 1 : 1;
                $bin->code = 'BIN-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
    public function rack() {
        return $this->belongsTo(Rack::class, 'rack_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
