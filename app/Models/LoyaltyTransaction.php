<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyTransaction extends Model
{
    protected $fillable = ['user_id', 'points_change', 'balance_after', 'reason', 'reference_id'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
