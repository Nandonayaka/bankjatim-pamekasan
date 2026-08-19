<?php

namespace App\Http\Controllers;

use App\Models\DisplayPrice;
use App\Models\Item;
use Illuminate\Http\Request;

class DisplayPriceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $query = DisplayPrice::with('item')
            ->orderByDesc('created_at');

        if ($search) {
            $query->whereHas('item', fn($q) => $q->where('item_name', 'like', "%{$search}%"));
        }

        $displays = $query->paginate(20)->withQueryString();

        // All items for the add-display modal dropdown
        $items = Item::orderBy('item_name')->get();

        return view('sales.index', compact('displays', 'items', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id'         => 'required|exists:items,id',
            'harga_asli'      => 'nullable|numeric|min:0',
            'harga_siap_jual' => 'required|numeric|min:0',
        ]);

        DisplayPrice::create([
            'item_id'         => $validated['item_id'],
            'harga_asli'      => $request->filled('harga_asli') ? (float) $validated['harga_asli'] : null,
            'harga_siap_jual' => (float) $validated['harga_siap_jual'],
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Harga display berhasil ditambahkan.');
    }

    public function destroy(string $id)
    {
        $display = DisplayPrice::findOrFail($id);
        $display->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Data display berhasil dihapus.');
    }
}
