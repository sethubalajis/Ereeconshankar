<?php

namespace App\Models;

use App\Traits\HasTransferBranch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TransferRequest extends Model
{
    use HasTransferBranch;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::created(function (self $model): void {
            if (! empty($model->transfer_request_number)) {
                return;
            }

            $model->forceFill([
                'transfer_request_number' => self::buildTransferRequestCode($model->id, $model->request_date),
            ])->saveQuietly();
        });
    }

    public static function buildTransferRequestCode(int $id, mixed $requestDate = null): string
    {
        $datePart = $requestDate
            ? Carbon::parse($requestDate)->format('Ymd')
            : now('Asia/Kolkata')->format('Ymd');

        return sprintf('TR-%s-%06d', $datePart, $id);
    }

    //
    public function fromBranch() {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }
    public function toBranch() {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    public function transferItems() {
        return $this->hasMany(TransferItem::class, 'transfer_request_id');
    }
}
