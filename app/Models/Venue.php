<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'address',
        'district',
        'city',
        'latitude',
        'longitude',
        'phone',
        'email',
        'featured_image',
        'gallery',
        'description',
        'opening_time',
        'closing_time',
        'is_active',
        'rating_avg',
        'reviews_count',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean',
        'rating_avg' => 'decimal:2',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function courts(): HasMany
    {
        return $this->hasMany(Court::class);
    }

    public function operatingHours(): HasMany
    {
        return $this->hasMany(OperatingHour::class);
    }

    public function pricingRules(): HasMany
    {
        return $this->hasMany(CourtPricingRule::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
