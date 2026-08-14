<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasBranch;

    protected $guarded = [];

    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
