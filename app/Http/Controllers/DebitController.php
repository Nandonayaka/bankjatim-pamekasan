<?php

namespace App\Http\Controllers;

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
                s.quantity,
                (COALESCE(p_avg.avg_unit_price, 0) * s.quantity) AS harga_kulaan,
                s.total_amount AS harga_jual,
                (s.total_amount - (COALESCE(p_avg.avg_unit_price, 0) * s.quantity)) AS laba
            FROM sales s
            JOIN items i ON s.item_id = i.id
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

        return view('debit.index', compact('records', 'totalLaba', 'search', 'month', 'monthOptions'));
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
