<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'venue_amenities');
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

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function staff(): HasMany
    {
        return $this->hasMany(StaffProfile::class);
    }

    /**
     * Determine if venue is currently open at a specific Carbon datetime or now
     */
    public function isOpen($at = null): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $time = $at ? \Carbon\Carbon::parse($at) : \Carbon\Carbon::now('Asia/Ho_Chi_Minh');
        $currentDay = $time->dayOfWeek; // 0 = Sunday, 1 = Monday, ... 6 = Saturday
        $currentTime = $time->format('H:i:s');
        $prevDay = ($currentDay + 6) % 7;

        // 1. Check operating hours relation
        $hours = $this->relationLoaded('operatingHours') ? $this->operatingHours : $this->operatingHours()->get();

        if ($hours->isNotEmpty()) {
            // Check today's schedule
            $todayHour = $hours->firstWhere('day_of_week', $currentDay);
            if ($todayHour && !$todayHour->is_closed) {
                $open = $todayHour->open_time;
                $close = $todayHour->close_time;
                if ($open <= $close) {
                    if ($currentTime >= $open && $currentTime <= $close) {
                        return true;
                    }
                } else {
                    // Cross-midnight shift (e.g. 18:00 to 02:00) - current time is after open
                    if ($currentTime >= $open) {
                        return true;
                    }
                }
            }

            // Check yesterday's shift running past midnight into today
            $yesterdayHour = $hours->firstWhere('day_of_week', $prevDay);
            if ($yesterdayHour && !$yesterdayHour->is_closed) {
                $openPrev = $yesterdayHour->open_time;
                $closePrev = $yesterdayHour->close_time;
                if ($openPrev > $closePrev && $currentTime <= $closePrev) {
                    return true;
                }
            }

            return false;
        }

        // 2. Fallback to venue opening_time and closing_time
        if ($this->opening_time && $this->closing_time) {
            $open = $this->opening_time;
            $close = $this->closing_time;
            if ($open <= $close) {
                return $currentTime >= $open && $currentTime <= $close;
            } else {
                return $currentTime >= $open || $currentTime <= $close;
            }
        }

        return true;
    }

    public function getIsOpenAttribute(): bool
    {
        return $this->isOpen();
    }
}
