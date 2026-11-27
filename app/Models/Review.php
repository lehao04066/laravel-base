<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Review extends Model
{
    protected $fillable = ['venue_id', 'user_id', 'booking_id', 'rating', 'comment', 'is_approved'];
    protected $casts = ['is_approved' => 'boolean'];
    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
    public function reply(): HasOne { return $this->hasOne(ReviewReply::class); }
}
