<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtPricingRule extends Model
{
    protected $fillable = [
        'venue_id',
        'court_type_id',
        'name',
        'day_type',
        'start_time',
        'end_time',
        'price_per_hour',
        'is_peak',
    ];

    protected $casts = [
        'is_peak' => 'boolean',
        'price_per_hour' => 'decimal:2',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function courtType(): BelongsTo
    {
        return $this->belongsTo(CourtType::class);
    }
}
