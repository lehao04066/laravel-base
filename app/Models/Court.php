<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Court extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'venue_id',
        'court_number',
        'name',
        'court_type_id',
        'surface_type',
        'is_active',
        'iot_relay_channel',
        'camera_stream_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function courtType(): BelongsTo
    {
        return $this->belongsTo(CourtType::class);
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function holds(): HasMany
    {
        return $this->hasMany(BookingHold::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(CourtMaintenance::class);
    }
}
