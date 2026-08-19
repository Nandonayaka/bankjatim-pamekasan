<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $month  = $request->get('month', '');

        $query = Sale::with('item')
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at');

        if ($search) {
            $query->whereHas('item', fn($q) => $q->where('item_name', 'like', "%{$search}%"));
        }

        if ($month) {
            try {
                $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                $end   = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
                $query->whereBetween('transaction_date', [$start, $end]);
            } catch (\Exception $e) {
                // invalid month format, ignore
            }
        }

        $sales = $query->paginate(15)->withQueryString();

        // Get all items (from purchases or stock/inventory) with current period final_stock
        $currentPeriod = Carbon::now()->format('Y-m');
        $items = Item::where(function($q) {
            $q->has('purchases')->orHas('inventory');
        })->orderBy('item_name')->get()->map(function ($item) use ($currentPeriod) {
            $inv = Inventory::where('item_id', $item->id)->where('period', $currentPeriod)->first();
            if (!$inv) {
                $inv = Inventory::where('item_id', $item->id)->orderByDesc('period')->first();
            }
            $item->current_stock = $inv ? $inv->final_stock : 0;
            return $item;
        });

        $monthOptions = $this->monthOptions();

        return view('sales.index', compact('sales', 'items', 'search', 'month', 'monthOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'item_id'          => 'required|exists:items,id',
            'quantity'         => 'required|integer|min:1',
            'total_amount'     => 'required|numeric|min:0',
        ]);

        // Cek stok barang tersedia untuk periode transaksi
        $period = Carbon::parse($validated['transaction_date'])->format('Y-m');
        $inv    = Inventory::getOrCreateForPeriod($validated['item_id'], $period);

        if ($validated['quantity'] > $inv->final_stock) {
            return back()->withInput()->with('error', "Gagal! Stok barang tidak mencukupi. Stok tersedia: {$inv->final_stock} pcs, Jumlah dijual: {$validated['quantity']} pcs.");
        }

        $unitPrice = $validated['quantity'] > 0 ? ($validated['total_amount'] / $validated['quantity']) : 0;

        DB::transaction(function () use ($validated, $unitPrice, $inv) {
            Sale::create([
                'item_id'          => $validated['item_id'],
                'transaction_date' => $validated['transaction_date'],
                'quantity'         => $validated['quantity'],
                'unit_price'       => $unitPrice,
                'total_amount'     => $validated['total_amount'],
            ]);

            // Update inventory
            $inv->total_out += $validated['quantity'];
            $inv->recalculate();
        });

        return redirect()->route('sales.index')
            ->with('success', 'Data penjualan berhasil ditambahkan.');
    }

    public function destroy(string $id)
    {
        $sale = Sale::findOrFail($id);

        DB::transaction(function () use ($sale) {
            $period = $sale->transaction_date->format('Y-m');
            $inv    = Inventory::where('item_id', $sale->item_id)
                ->where('period', $period)
                ->first();

            if ($inv) {
                $inv->total_out = max(0, $inv->total_out - $sale->quantity);
                $inv->recalculate();
            }

            $sale->delete();
        });

        return redirect()->route('sales.index')
            ->with('success', 'Data penjualan berhasil dihapus.');
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
