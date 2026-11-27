<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchParticipant extends Model
{
    protected $fillable = [
        'match_post_id',
        'user_id',
        'status',
        'deposit_status',
    ];

    public function matchPost(): BelongsTo
    {
        return $this->belongsTo(MatchPost::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
