<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringContract extends Model
{
    protected $fillable = ['user_id', 'venue_id', 'court_id', 'day_of_week', 'start_time', 'end_time', 'start_date', 'end_date', 'weekly_amount', 'status', 'notes'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'weekly_amount' => 'decimal:2'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }
    public function court(): BelongsTo { return $this->belongsTo(Court::class); }
}
