<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
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

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function isClient(): bool
    {
        return in_array($this->role, ['client', 'customer']);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function matchPosts(): HasMany
    {
        return $this->hasMany(MatchPost::class);
    }

    public function matchParticipations(): HasMany
    {
        return $this->hasMany(MatchParticipant::class);
    }
}
