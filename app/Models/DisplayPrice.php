<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisplayPrice extends Model
{
    use HasUuids;

    protected $fillable = [
        'item_id',
        'harga_asli',
        'harga_siap_jual',
    ];

    protected $casts = [
        'harga_asli'      => 'decimal:2',
        'harga_siap_jual' => 'decimal:2',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
