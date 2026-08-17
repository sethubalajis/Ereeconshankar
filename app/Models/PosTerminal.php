<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class PosTerminal extends Model
{
    use HasBranch;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (self $posTerminal): void {
            if (empty($posTerminal->terminal_code)) {
                $lastTerminal = self::orderBy('id', 'desc')->first();
                $nextId = $lastTerminal ? $lastTerminal->id + 1 : 1;
                $posTerminal->terminal_code = 'TERM-' . str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
