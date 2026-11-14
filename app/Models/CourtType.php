<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourtType extends Model
{
    protected $fillable = ['name', 'description', 'is_premium'];

    protected $casts = [
        'is_premium' => 'boolean',
    ];

    public function courts(): HasMany
    {
        return $this->hasMany(Court::class);
    }
}
