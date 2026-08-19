<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $items = Item::all()->keyBy('item_code');

        // Selling prices (slightly above purchase price for margin)
        $prices = [
            'BRG-001' => 33000,  // Minyak Goreng   (+5rb margin)
            'BRG-002' => 16000,  // Gula Pasir       (+2.5rb)
            'BRG-003' => 82000,  // Beras Premium    (+10rb)
            'BRG-004' => 13500,  // Tepung Terigu    (+2.5rb)
            'BRG-005' => 10000,  // Sabun Cuci       (+2.5rb)
            'BRG-006' => 30000,  // Deterjen         (+5rb)
            'BRG-007' => 22000,  // Kecap            (+4rb)
            'BRG-008' => 3700,   // Mie Instan       (+500)
        ];

        // Generate multiple sales transactions per item per month
        foreach (range(2, 0) as $monthsAgo) {
            $baseDate = Carbon::now()->subMonths($monthsAgo);
            $period   = $baseDate->format('Y-m');
            $daysInMonth = $baseDate->daysInMonth;

            foreach ($items as $code => $item) {
                $sellPrice = $prices[$code] ?? 15000;

                // 3–5 sales transactions per item per month
                $txCount = rand(3, 5);
                for ($t = 0; $t < $txCount; $t++) {
                    $qty    = rand(5, 20);
                    $txDate = $baseDate->copy()->setDay(rand(1, $daysInMonth))->toDateString();

                    Sale::create([
                        'item_id'          => $item->id,
                        'transaction_date' => $txDate,
                        'quantity'         => $qty,
                        'unit_price'       => $sellPrice,
                    ]);

                    // Sync inventory
                    $inv = Inventory::getOrCreateForPeriod($item->id, $period);
                    $inv->total_out += $qty;
                    $inv->recalculate();
                }
            }
        }
    }
}
