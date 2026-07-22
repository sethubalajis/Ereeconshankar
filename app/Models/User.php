<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class User extends Authenticatable implements HasTenants
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function branchAccesses() {
        return $this->hasMany(UserBranchAccess::class);
    }
    public function loginHistories() {
        return $this->hasMany(LoginHistory::class);
    }
    
    public function branches() {
        return $this->belongsToMany(Branch::class, 'user_branch_accesses', 'user_id', 'branch_id');
    }

    public function hasBranchAccessTo(int $branchId): bool
    {
        return $this->branches()->where('branches.id', $branchId)->exists();
    }

    public function grantBranchAccess(int $branchId): void
    {
        if (! $this->hasBranchAccessTo($branchId)) {
            $this->branches()->attach($branchId);
        }
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->branches;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->branches()->where('branches.id', $tenant->id)->exists();
    }
}