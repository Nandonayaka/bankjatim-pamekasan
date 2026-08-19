<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebitController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $month  = $request->get('month', '');

        $conditions = [];
        $bindings   = [];

        if ($search) {
            $conditions[] = "i.item_name LIKE ?";
            $bindings[]   = "%{$search}%";
        }

        if ($month) {
            try {
                $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth()->toDateString();
                $end   = Carbon::createFromFormat('Y-m', $month)->endOfMonth()->toDateString();
                $conditions[] = "s.transaction_date BETWEEN ? AND ?";
                $bindings[]   = $start;
                $bindings[]   = $end;
            } catch (\Exception $e) {}
        }

        $where = count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $baseSql = "
            SELECT
                s.id,
                s.transaction_date,
                i.item_name,
                s.customer_name,
                s.quantity,
                COALESCE(NULLIF(dp_latest.harga_asli, 0), p_avg.avg_unit_price, 0) AS harga_satuan,
                (COALESCE(NULLIF(dp_latest.harga_asli, 0), p_avg.avg_unit_price, 0) * s.quantity) AS modal,
                s.total_amount AS harga_jual,
                (s.total_amount - (COALESCE(NULLIF(dp_latest.harga_asli, 0), p_avg.avg_unit_price, 0) * s.quantity)) AS laba
            FROM sales s
            JOIN items i ON s.item_id = i.id
            LEFT JOIN (
                SELECT dp1.item_id, dp1.harga_asli
                FROM display_prices dp1
                INNER JOIN (
                    SELECT item_id, MAX(created_at) AS max_created
                    FROM display_prices
                    GROUP BY item_id
                ) dp_max ON dp1.item_id = dp_max.item_id AND dp1.created_at = dp_max.max_created
            ) dp_latest ON s.item_id = dp_latest.item_id
            LEFT JOIN (
                SELECT item_id, AVG(unit_price) AS avg_unit_price
                FROM purchases
                GROUP BY item_id
            ) p_avg ON s.item_id = p_avg.item_id
            {$where}
            ORDER BY s.transaction_date DESC
        ";


        // Total laba
        $totalLaba = DB::selectOne(
            "SELECT COALESCE(SUM(laba), 0) AS total FROM ({$baseSql}) AS sub",
            $bindings
        )->total ?? 0;

        // Paginate
        $perPage     = 15;
        $currentPage = (int) $request->get('page', 1);
        $offset      = ($currentPage - 1) * $perPage;

        $rows  = DB::select($baseSql . " LIMIT {$perPage} OFFSET {$offset}", $bindings);
        $total = DB::selectOne(
            "SELECT COUNT(*) AS cnt FROM ({$baseSql}) AS sub",
            $bindings
        )->cnt ?? 0;

        $records = new \Illuminate\Pagination\LengthAwarePaginator(
            $rows,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $monthOptions = $this->monthOptions();

        // Items untuk form kasir
        $currentPeriod = Carbon::now()->format('Y-m');
        $avgPrices = Purchase::selectRaw('item_id, AVG(unit_price) as avg_price')
            ->groupBy('item_id')
            ->pluck('avg_price', 'item_id')
            ->toArray();

        $items = Item::where(function($q) {
            $q->has('purchases')->orHas('inventory');
        })->orderBy('item_name')->get()->map(function ($item) use ($currentPeriod, $avgPrices) {
            $inv = Inventory::where('item_id', $item->id)->where('period', $currentPeriod)->first();
            if (!$inv) {
                $inv = Inventory::where('item_id', $item->id)->orderByDesc('period')->first();
            }
            $item->current_stock      = $inv ? $inv->final_stock : 0;
            $item->avg_purchase_price = $avgPrices[$item->id] ?? 0;
            return $item;
        });

        return view('debit.index', compact('records', 'totalLaba', 'search', 'month', 'monthOptions', 'items'));
    }

    private function monthOptions(): array
    {
        $options = [];
        for ($i = 11; $i >= 0; $i--) {
            $m         = Carbon::now()->subMonths($i);
            $options[] = ['value' => $m->format('Y-m'), 'label' => $m->translatedFormat('F Y')];
        }
        return $options;
    }
}
