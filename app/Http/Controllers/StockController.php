<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $month  = $request->get('month', Carbon::now()->format('Y-m'));

        $query = Inventory::with('item')
            ->where('period', $month)
            ->orderByDesc('stock_date')
            ->orderByDesc('created_at');

        if ($search) {
            $query->whereHas('item', fn($q) => $q->where('item_name', 'like', "%{$search}%"));
        }

        $stocks   = $query->paginate(15)->withQueryString();
        $allItems = Item::orderBy('item_name')->get();

        $monthOptions = $this->monthOptions();

        return view('stock.index', compact('stocks', 'allItems', 'search', 'month', 'monthOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stock_date'    => 'required|date',
            'item_name'     => 'required|string|max:255',
            'initial_stock' => 'required|integer|min:0',
        ]);

        $dateStr = Carbon::parse($validated['stock_date'])->toDateString();
        $period  = Carbon::parse($dateStr)->format('Y-m');

        DB::transaction(function () use ($validated, $dateStr) {
            // Find existing item by name or create new one
            $item = Item::whereRaw('LOWER(item_name) = ?', [strtolower(trim($validated['item_name']))])->first();
            if (!$item) {
                $item = Item::create([
                    'item_name' => trim($validated['item_name']),
                    'item_code' => 'BRG-' . str_pad(Item::count() + 1, 3, '0', STR_PAD_LEFT),
                ]);
            }

            // Get or create inventory for item & date
            $inv = Inventory::getOrCreateForDate($item->id, $dateStr);
            $inv->initial_stock = (int) $validated['initial_stock'];
            $inv->recalculate();
        });

        return redirect()->route('stock.index', ['month' => $period])
            ->with('success', 'Data persediaan stok berhasil disimpan.');
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'initial_stock' => 'required|integer|min:0',
        ]);

        $inv = Inventory::findOrFail($id);

        DB::transaction(function () use ($inv, $validated) {
            $inv->initial_stock = (int) $validated['initial_stock'];
            $inv->recalculate();
        });

        return redirect()->route('stock.index', ['month' => $inv->period])
            ->with('success', 'Sisa barang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $inv = Inventory::findOrFail($id);
        $period = $inv->period;
        $inv->delete();

        return redirect()->route('stock.index', ['month' => $period])
            ->with('success', 'Data stok berhasil dihapus.');
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
