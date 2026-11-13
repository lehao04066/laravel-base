<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
