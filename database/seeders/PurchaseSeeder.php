<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $items = Item::all()->keyBy('item_code');

        // Unit purchase prices per item
        $prices = [
            'BRG-001' => 28000,  // Minyak Goreng
            'BRG-002' => 13500,  // Gula Pasir
            'BRG-003' => 72000,  // Beras Premium
            'BRG-004' => 11000,  // Tepung Terigu
            'BRG-005' => 7500,   // Sabun Cuci
            'BRG-006' => 25000,  // Deterjen
            'BRG-007' => 18000,  // Kecap
            'BRG-008' => 3200,   // Mie Instan
        ];

        // Generate purchases for last 3 months
        foreach (range(2, 0) as $monthsAgo) {
            $baseDate = Carbon::now()->subMonths($monthsAgo);
            $period   = $baseDate->format('Y-m');

            foreach ($items as $code => $item) {
                $qty        = rand(30, 100);
                $unitPrice  = $prices[$code] ?? 10000;
                $totalAmt   = $qty * $unitPrice;

                $txDate = $baseDate->copy()->setDay(rand(1, 15))->toDateString();

                Purchase::create([
                    'item_id'          => $item->id,
                    'transaction_date' => $txDate,
                    'quantity'         => $qty,
                    'unit_price'       => $unitPrice,
                    'total_amount'     => $totalAmt,
                ]);

                // Sync inventory
                $inv = Inventory::getOrCreateForPeriod($item->id, $period);
                $inv->total_in += $qty;
                $inv->recalculate();
            }
        }
    }
}
