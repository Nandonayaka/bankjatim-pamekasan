<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['item_code' => 'BRG-001', 'item_name' => 'Minyak Goreng Bimoli 2L'],
            ['item_code' => 'BRG-002', 'item_name' => 'Gula Pasir 1kg'],
            ['item_code' => 'BRG-003', 'item_name' => 'Beras Premium 5kg'],
            ['item_code' => 'BRG-004', 'item_name' => 'Tepung Terigu Segitiga 1kg'],
            ['item_code' => 'BRG-005', 'item_name' => 'Sabun Cuci Piring'],
            ['item_code' => 'BRG-006', 'item_name' => 'Deterjen Rinso 1kg'],
            ['item_code' => 'BRG-007', 'item_name' => 'Kecap Manis ABC 600ml'],
            ['item_code' => 'BRG-008', 'item_name' => 'Mie Instan Indomie'],
        ];

        foreach ($items as $item) {
            Item::firstOrCreate(['item_code' => $item['item_code']], $item);
        }
    }
}
