<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasUuids;

    protected $fillable = [
        'item_id',
        'transaction_date',
        'quantity',
        'unit_price',
        'total_amount',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'quantity'         => 'integer',
        'unit_price'       => 'decimal:2',
        'total_amount'     => 'decimal:2',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
