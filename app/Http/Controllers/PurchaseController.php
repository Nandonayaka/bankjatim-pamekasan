<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $month  = $request->get('month', '');

        $query = Purchase::with('item')
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
                // ignore
            }
        }

        $purchases = $query->paginate(15)->withQueryString();
        $items     = Item::orderBy('item_name')->get();
        $monthOptions = $this->monthOptions();

        return view('purchases.index', compact('purchases', 'items', 'search', 'month', 'monthOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'item_name'        => 'required|string|max:255',
            'quantity'         => 'required|integer|min:1',
            'total_amount'     => 'required|numeric|min:0',
        ]);

        // Find existing item by name (case-insensitive) or create new one
        $item = Item::whereRaw('LOWER(item_name) = ?', [strtolower(trim($validated['item_name']))])->first();
        if (!$item) {
            $item = Item::create([
                'item_name' => trim($validated['item_name']),
                'item_code' => 'BRG-' . str_pad(Item::count() + 1, 3, '0', STR_PAD_LEFT),
            ]);
        }

        $unitPrice = $validated['quantity'] > 0 ? ($validated['total_amount'] / $validated['quantity']) : 0;

        DB::transaction(function () use ($validated, $item, $unitPrice) {
            Purchase::create([
                'item_id'          => $item->id,
                'transaction_date' => $validated['transaction_date'],
                'quantity'         => $validated['quantity'],
                'unit_price'       => $unitPrice,
                'total_amount'     => $validated['total_amount'],
            ]);

            // Update inventory
            $inv = Inventory::getOrCreateForDate($item->id, $validated['transaction_date']);
            $inv->total_in += $validated['quantity'];
            $inv->recalculate();
        });

        return redirect()->route('purchases.index')
            ->with('success', 'Data kulaan berhasil ditambahkan.');
    }

    public function destroy(string $id)
    {
        $purchase = Purchase::findOrFail($id);

        DB::transaction(function () use ($purchase) {
            $inv = Inventory::where('item_id', $purchase->item_id)
                ->where('stock_date', $purchase->transaction_date->toDateString())
                ->first();

            if (!$inv) {
                $inv = Inventory::where('item_id', $purchase->item_id)
                    ->where('period', $purchase->transaction_date->format('Y-m'))
                    ->first();
            }

            if ($inv) {
                $inv->total_in = max(0, $inv->total_in - $purchase->quantity);
                $inv->recalculate();
            }

            $purchase->delete();
        });

        return redirect()->route('purchases.index')
            ->with('success', 'Data kulaan berhasil dihapus.');
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
