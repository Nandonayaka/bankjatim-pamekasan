<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Kasir: simpan transaksi penjualan ke tabel sales, kurangi stok inventori.
     * Hanya dipanggil dari halaman Debit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'item_id'          => 'required|exists:items,id',
            'customer_name'    => 'nullable|string|max:255',
            'quantity'         => 'required|integer|min:1',
            'total_amount'     => 'required|numeric|min:0',
        ]);

        $totalAmount = (float) $validated['total_amount'];
        $quantity    = (int)   $validated['quantity'];
        $unitPrice   = $quantity > 0 ? ($totalAmount / $quantity) : 0;

        // Cek stok
        $inv = Inventory::getOrCreateForDate($validated['item_id'], $validated['transaction_date']);

        if ($quantity > $inv->final_stock) {
            return back()->withInput()->with(
                'error',
                "Gagal! Stok tidak mencukupi. Stok tersedia: {$inv->final_stock} pcs, diminta: {$quantity} pcs."
            );
        }

        DB::transaction(function () use ($validated, $unitPrice, $totalAmount, $inv) {
            Sale::create([
                'item_id'          => $validated['item_id'],
                'customer_name'    => $validated['customer_name'] ?? null,
                'transaction_date' => $validated['transaction_date'],
                'quantity'         => $validated['quantity'],
                'harga_asli'       => null,   // dihitung otomatis dari avg purchase di DebitController
                'unit_price'       => $unitPrice,
                'total_amount'     => $totalAmount,
            ]);

            $inv->total_out += $validated['quantity'];
            $inv->recalculate();
        });

        return redirect()->route('debit.index')
            ->with('success', 'Transaksi kasir berhasil dicatat.');
    }

    /**
     * Kasir: hapus transaksi penjualan, kembalikan stok inventori.
     * Hanya dipanggil dari halaman Debit.
     */
    public function destroy(string $id)
    {
        $sale = Sale::findOrFail($id);

        DB::transaction(function () use ($sale) {
            $inv = Inventory::where('item_id', $sale->item_id)
                ->where('stock_date', $sale->transaction_date->toDateString())
                ->first();

            if (!$inv) {
                $inv = Inventory::where('item_id', $sale->item_id)
                    ->where('period', $sale->transaction_date->format('Y-m'))
                    ->first();
            }

            if ($inv) {
                $inv->total_out = max(0, $inv->total_out - $sale->quantity);
                $inv->recalculate();
            }

            $sale->delete();
        });

        return redirect()->route('debit.index')
            ->with('success', 'Data transaksi berhasil dihapus.');
    }
}
