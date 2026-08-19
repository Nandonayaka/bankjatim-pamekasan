<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasUuids;

    protected $table = 'inventory';

    protected $fillable = [
        'item_id',
        'stock_date',
        'period',
        'initial_stock',
        'total_in',
        'total_out',
        'final_stock',
    ];

    protected $casts = [
        'stock_date'    => 'date',
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
     * Get or create inventory record for a specific item and date (Y-m-d).
     */
    public static function getOrCreateForDate(string $itemId, string $dateStr): self
    {
        $dateStr = Carbon::parse($dateStr)->toDateString();
        $period  = Carbon::parse($dateStr)->format('Y-m');

        $inv = self::firstOrCreate(
            ['item_id' => $itemId, 'stock_date' => $dateStr],
            ['period' => $period, 'initial_stock' => 0, 'total_in' => 0, 'total_out' => 0, 'final_stock' => 0]
        );

        // If new date record, carry over final_stock from previous date record as initial_stock
        if ($inv->wasRecentlyCreated) {
            $prevInv = self::where('item_id', $itemId)
                ->where('stock_date', '<', $dateStr)
                ->orderByDesc('stock_date')
                ->first();

            if ($prevInv) {
                $inv->initial_stock = $prevInv->final_stock;
                $inv->final_stock   = $prevInv->final_stock;
                $inv->save();
            }
        }

        return $inv;
    }

    /**
     * Legacy helper: Get or create inventory record for a given item and period (Y-m).
     */
    public static function getOrCreateForPeriod(string $itemId, string $period): self
    {
        $dateStr = $period . '-01';
        return self::getOrCreateForDate($itemId, $dateStr);
    }
}
