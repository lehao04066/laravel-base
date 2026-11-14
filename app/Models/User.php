<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin') || $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin']);
    }

    public function isOwner(): bool
    {
        return $this->hasAnyRole(['super_admin', 'owner']);
    }

    public function isStaff(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin', 'owner', 'staff']);
    }

    public function isCustomer(): bool
    {
        return $this->hasRole('customer') || $this->role === 'customer';
    }

    public function isCoach(): bool
    {
        return $this->hasRole('coach') || $this->role === 'coach';
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')->withPivot('venue_id')->withTimestamps();
    }

    public function hasRole(string $role): bool
    {
        return $this->hasAnyRole([$role]);
    }

    /** @param array<int, string> $roles */
    public function hasAnyRole(array $roles): bool
    {
        $assignedRoles = $this->roles->pluck('name')->all();

        if ($assignedRoles !== []) {
            return count(array_intersect($roles, $assignedRoles)) > 0;
        }

        return in_array($this->role, $roles, true);
    }

    public function matchPosts(): HasMany
    {
        return $this->hasMany(MatchPost::class);
    }
}
