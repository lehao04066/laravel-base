<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentEvent extends Model
{
    protected $fillable = ['payment_transaction_id', 'event_type', 'payload', 'ip_address'];

    protected $casts = ['payload' => 'array'];
}
