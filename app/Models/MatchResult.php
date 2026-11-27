<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchResult extends Model
{
    protected $fillable = ['user_id', 'venue_id', 'opponent_name', 'score', 'result', 'played_at', 'notes'];
    protected $casts = ['played_at' => 'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }
}
