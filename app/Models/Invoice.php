<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_code', 'booking_id', 'pos_order_id', 'customer_name', 'customer_phone',
        'subtotal', 'tax_amount', 'total', 'payment_method', 'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];
}
