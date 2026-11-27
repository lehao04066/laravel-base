<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipTier extends Model
{
    protected $fillable = ['name', 'slug', 'min_spending', 'discount_percentage', 'priority_booking_days', 'perk_description'];
    protected $casts = ['min_spending' => 'decimal:2'];

    public function memberships(): HasMany { return $this->hasMany(UserMembership::class); }
}
