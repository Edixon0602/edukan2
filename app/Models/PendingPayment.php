<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'item_name',
    'amount',
    'payment_method',
    'receipt_path',
    'status',
])]
class PendingPayment extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
