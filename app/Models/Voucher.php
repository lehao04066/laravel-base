<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'title',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function redemptions(): HasMany
    {
        return $this->hasMany(VoucherRedemption::class);
    }

    public function isValidFor(float $orderAmount): bool
    {
        return $this->is_active
            && $this->start_date?->lte(now()->toDateString())
            && $this->end_date?->gte(now()->toDateString())
            && $this->used_count < $this->usage_limit
            && $orderAmount >= (float) $this->min_order_amount;
    }

    public function calculateDiscount(float $orderAmount): float
    {
        if (! $this->isValidFor($orderAmount)) {
            return 0.00;
        }

        if ($this->discount_type === 'percent') {
            $discount = $orderAmount * ((float) $this->discount_value / 100);

            if ($this->max_discount_amount && $discount > (float) $this->max_discount_amount) {
                $discount = (float) $this->max_discount_amount;
            }

            return $discount;
        }

        return min((float) $this->discount_value, $orderAmount);
    }
}
