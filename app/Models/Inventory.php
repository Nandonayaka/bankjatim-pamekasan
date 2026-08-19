<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasUuids;

    protected $table = 'inventory';

    protected $fillable = [
        'item_id',
        'period',
        'initial_stock',
        'total_in',
        'total_out',
        'final_stock',
    ];

    protected $casts = [
        'initial_stock' => 'integer',
        'total_in'      => 'integer',
        'total_out'     => 'integer',
        'final_stock'   => 'integer',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Recalculate final_stock based on formula:
     * final_stock = initial_stock + total_in - total_out
     */
    public function recalculate(): void
    {
        $this->final_stock = $this->initial_stock + $this->total_in - $this->total_out;
        $this->save();
    }

    /**
     * Get or create inventory record for a given item and period.
     */
    public static function getOrCreateForPeriod(string $itemId, string $period): self
    {
        $inv = self::firstOrCreate(
            ['item_id' => $itemId, 'period' => $period],
            ['initial_stock' => 0, 'total_in' => 0, 'total_out' => 0, 'final_stock' => 0]
        );

        // If new period, carry over final_stock from previous period as initial_stock
        if ($inv->wasRecentlyCreated) {
            $prevDate    = \Carbon\Carbon::createFromFormat('Y-m', $period)->subMonth();
            $prevPeriod  = $prevDate->format('Y-m');
            $prevInv     = self::where('item_id', $itemId)->where('period', $prevPeriod)->first();

            if ($prevInv) {
                $inv->initial_stock = $prevInv->final_stock;
                $inv->final_stock   = $prevInv->final_stock;
                $inv->save();
            }
        }

        return $inv;
    }
}
