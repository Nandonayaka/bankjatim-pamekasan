<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));

        // Parse period
        try {
            $periodCarbon = Carbon::createFromFormat('Y-m', $month);
        } catch (\Exception $e) {
            $periodCarbon = Carbon::now();
            $month        = Carbon::now()->format('Y-m');
        }

        $periodStart = $periodCarbon->copy()->startOfMonth();
        $periodEnd   = $periodCarbon->copy()->endOfMonth();
        $targetYear  = $periodCarbon->year;

        // KPI 1: Total Kulaan (Rp) for selected month
        $totalKulaan = (float) Purchase::whereBetween('transaction_date', [$periodStart, $periodEnd])
            ->sum('total_amount');

        // KPI 2: Barang Terjual (Pcs) for selected month
        $totalTerjual = (int) Sale::whereBetween('transaction_date', [$periodStart, $periodEnd])
            ->sum('quantity');

        // KPI 3: Total Laba (Rp) for selected month
        $labaQuery = DB::select("
            SELECT COALESCE(SUM((s.unit_price - COALESCE(p_avg.avg_unit_price, 0)) * s.quantity), 0) as laba
            FROM sales s
            LEFT JOIN (
                SELECT item_id, AVG(unit_price) as avg_unit_price
                FROM purchases
                GROUP BY item_id
            ) p_avg ON s.item_id = p_avg.item_id
            WHERE s.transaction_date BETWEEN ? AND ?
        ", [$periodStart->toDateString(), $periodEnd->toDateString()]);
        $totalLaba = (float) ($labaQuery[0]->laba ?? 0);

        // KPI 4: Total Stok (Pcs) for selected month
        $totalStok = (int) Inventory::where('period', $month)->sum('final_stock');

        // Proportions calculation for selected month
        $grandTotal = $totalKulaan + $totalTerjual + $totalLaba + $totalStok;

        if ($grandTotal > 0) {
            $propKulaan  = (int) round(($totalKulaan  / $grandTotal) * 100);
            $propTerjual = (int) round(($totalTerjual / $grandTotal) * 100);
            $propLaba    = (int) round(($totalLaba    / $grandTotal) * 100);
            $propStok    = (int) round(($totalStok    / $grandTotal) * 100);
            $isZero      = false;
        } else {
            $propKulaan  = 0;
            $propTerjual = 0;
            $propLaba    = 0;
            $propStok    = 0;
            $isZero      = true;
        }

        // Line Chart — 12 Months of the target year (Jan - Des)
        $monthsAbbr = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartSalesData = [];
        for ($m = 1; $m <= 12; $m++) {
            $sum = (float) Sale::whereYear('transaction_date', $targetYear)
                ->whereMonth('transaction_date', $m)
                ->sum('total_amount');
            $chartSalesData[] = $sum;
        }

        // Month picker options (last 12 months)
        $monthOptions = [];
        for ($i = 0; $i < 12; $i++) {
            $m = Carbon::now()->subMonths($i);
            $monthOptions[] = [
                'value' => $m->format('Y-m'),
                'label' => $m->translatedFormat('F Y'),
            ];
        }

        return view('dashboard.index', compact(
            'totalKulaan', 'totalTerjual', 'totalLaba', 'totalStok',
            'monthsAbbr', 'chartSalesData',
            'propKulaan', 'propTerjual', 'propLaba', 'propStok', 'isZero',
            'month', 'monthOptions'
        ));
    }
}
