<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletLedgerEntry extends Model
{
    protected $fillable = [
        'wallet_account_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'reference_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function walletAccount(): BelongsTo
    {
        return $this->belongsTo(WalletAccount::class);
    }
}
