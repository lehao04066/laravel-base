<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = ['transaction_code', 'booking_id', 'user_id', 'gateway', 'amount', 'currency', 'status', 'gateway_transaction_id', 'gateway_response', 'paid_at'];
    protected $casts = ['amount' => 'decimal:2', 'gateway_response' => 'array', 'paid_at' => 'datetime'];
}
