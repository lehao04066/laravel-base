<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatchPost extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'venue_id',
        'title',
        'play_datetime',
        'duration_hours',
        'target_level',
        'players_needed',
        'players_joined',
        'fee_per_player',
        'description',
        'status',
    ];

    protected $casts = [
        'play_datetime' => 'datetime',
        'duration_hours' => 'decimal:1',
        'fee_per_player' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(MatchParticipant::class);
    }
}
