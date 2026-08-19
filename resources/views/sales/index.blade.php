@extends('layouts.app')

@section('title', 'Selling price')
@section('page_title', 'Selling price')
@section('page_subtitle', 'Harga penjualan barang')

@section('content')

{{-- Sub-header Action Bar --}}
<div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-3.5 mb-5 sm:mb-6 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 min-h-[64px]">
    <form method="GET" action="{{ route('sales.index') }}" class="flex items-center gap-2.5 w-full sm:w-auto flex-1 max-w-sm">
        <div class="relative w-full">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" name="search" class="w-full bg-white border border-slate-300 rounded-full py-2 pl-10 pr-4 text-xs text-slate-800 outline-none focus:border-[#C8102E] focus:ring-2 focus:ring-[#C8102E]/15 transition-all placeholder:text-slate-400" placeholder="Search"
                   value="{{ $search }}" id="search-input">
        </div>
        @if($month)
            <input type="hidden" name="month" value="{{ $month }}">
        @endif
    </form>
    <div class="flex items-center gap-2.5 w-full sm:w-auto">
        <button class="flex-1 sm:flex-none inline-flex items-center justify-center gap-[10px] px-[15px] py-[10px] h-[37px] bg-[#C8102E] hover:bg-[#a30a25] text-white rounded-[10px] text-xs font-semibold transition-all shadow-xs" id="btn-open-modal">
            + Tambah barang
        </button>
        <a href="{{ route('dashboard') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-[10px] px-[15px] py-[10px] h-[37px] bg-white border border-[#C8102E] text-[#C8102E] hover:bg-rose-50 rounded-[10px] text-xs font-semibold transition-all">
            ← Kembali
        </a>
    </div>
</div>

{{-- Table Card --}}
<div class="bg-white border border-slate-200 rounded-[16px] p-4 sm:p-6 shadow-xs">
    <h3 class="text-base font-bold text-slate-800 mb-4">Total bulanan</h3>

    <div class="overflow-x-auto border border-slate-200 rounded-[16px] -mx-1 sm:mx-0">
        <table class="min-w-[640px] w-full text-left border-collapse table-fixed">
            <thead>
                <tr class="bg-[#C8102E] text-white h-[40px] rounded-t-[8px]">
                    <th class="w-[15%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle first:rounded-tl-[8px]">Tgl</th>
                    <th class="w-[35%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Nama barang</th>
                    <th class="w-[25%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Harga barang</th>
                    <th class="w-[15%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Jumlah barang terjual</th>
                    <th class="w-[10%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle text-right last:rounded-tr-[8px]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-[#4B5563]">
                @forelse($sales as $i => $s)
                    <tr class="hover:bg-slate-50/80 transition-colors h-[52px]">
                        <td class="py-3 px-3 sm:px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">{{ $s->transaction_date->format('j / m / y') }}</td>
                        <td class="py-3 px-3 sm:px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">{{ strtolower($s->item?->item_name ?? '-') }}</td>
                        <td class="py-3 px-3 sm:px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">RP . {{ number_format($s->total_amount, 0, ',', '.') }}</td>
                        <td class="py-3 px-3 sm:px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">{{ number_format($s->quantity, 0, ',', '.') }}</td>
                        <td class="py-3 px-3 sm:px-4 text-right border-b border-slate-200 align-middle">
                            <form method="POST" action="{{ route('sales.destroy', $s->id) }}"
                                  onsubmit="return confirm('Hapus data penjualan ini?')" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-md transition-colors" title="Hapus">
                                    <svg class="w-3.5 h-3.5 stroke-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14H6L5 6"/>
                                        <path d="M10 11v6M14 11v6"/>
                                        <path d="M9 6V4h6v2"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-[14px] font-medium text-slate-400">
                            Belum ada data penjualan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($sales->hasPages())
        <div class="flex items-center justify-center gap-1.5 mt-6 text-xs font-bold flex-wrap">
            @if($sales->onFirstPage())
                <span class="w-7 h-7 flex items-center justify-center text-slate-300 pointer-events-none">‹</span>
            @else
                <a href="{{ $sales->previousPageUrl() }}" class="w-7 h-7 flex items-center justify-center text-[#C8102E] hover:bg-rose-50 rounded">‹</a>
            @endif

            @foreach($sales->getUrlRange(1, min(3, $sales->lastPage())) as $page => $url)
                @if($page == $sales->currentPage())
                    <span class="w-7 h-7 bg-[#C8102E] text-white rounded flex items-center justify-center shadow-xs">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-7 h-7 text-slate-700 hover:bg-slate-100 rounded flex items-center justify-center">{{ $page }}</a>
                @endif
            @endforeach

            @if($sales->lastPage() > 3)
                <span class="w-7 h-7 flex items-center justify-center text-slate-400">...</span>
                <a href="{{ $sales->url($sales->lastPage()) }}" class="w-7 h-7 text-slate-700 hover:bg-slate-100 rounded flex items-center justify-center">{{ $sales->lastPage() }}</a>
            @endif

            @if($sales->hasMorePages())
                <a href="{{ $sales->nextPageUrl() }}" class="w-7 h-7 flex items-center justify-center text-[#C8102E] hover:bg-rose-50 rounded">›</a>
            @else
                <span class="w-7 h-7 flex items-center justify-center text-slate-300 pointer-events-none">›</span>
            @endif
        </div>
    @endif
</div>

{{-- Modal: Tambah Barang --}}
<div class="modal-overlay fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 flex items-center justify-center p-3.5 sm:p-5" id="modal-overlay">
    <div class="modal-card bg-white border border-slate-200 rounded-2xl w-full max-w-lg shadow-2xl p-5 sm:p-6 relative max-h-[90vh] overflow-y-auto">
        <button class="absolute top-4 sm:top-5 right-4 sm:right-5 text-slate-400 hover:text-slate-700 transition-colors" id="btn-close-modal" aria-label="Tutup">
            <svg class="w-5 h-5 stroke-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>

        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 mb-5 sm:mb-6">Tambah barang</h3>

        <form method="POST" action="{{ route('sales.store') }}" id="sale-form">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5" for="transaction_date">Tanggal</label>
                    <input type="date" name="transaction_date" id="transaction_date"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 outline-none focus:border-[#C8102E]"
                           value="{{ date('Y-m-d') }}" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5" for="item_id">Nama barang</label>
                    <select name="item_id" id="item_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 outline-none focus:border-[#C8102E]" required onchange="handleItemSelect(this)">
                        <option value="">......</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" data-stock="{{ $item->current_stock }}">
                                {{ $item->item_name }} (Stok: {{ $item->current_stock }} pcs)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5" for="quantity">Jumlah barang</label>
                    <input type="number" name="quantity" id="quantity"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 outline-none focus:border-[#C8102E] placeholder:text-slate-400"
                           min="1" placeholder="0" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-700 mb-1.5" for="total_amount">Nominal</label>
                <input type="number" name="total_amount" id="total_amount"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-[#C8102E] placeholder:text-slate-400"
                       min="0" step="0.01" placeholder="RP .000" required>
                <div id="stock-hint" class="text-xs text-slate-500 mt-1 font-medium"></div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-[10px] px-[20px] py-[10px] h-[37px] bg-[#C8102E] hover:bg-[#a30a25] text-white rounded-[10px] text-xs font-bold shadow-md transition-all">
                    + Tambah
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const overlay   = document.getElementById('modal-overlay');
    const openBtn   = document.getElementById('btn-open-modal');
    const closeBtn  = document.getElementById('btn-close-modal');

    function openModal()  { overlay.classList.add('open'); }
    function closeModal() { overlay.classList.remove('open'); }

    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });

    @if($errors->any() || session('error')) openModal(); @endif

    function handleItemSelect(select) {
        const selectedOpt = select.options[select.selectedIndex];
        const stockHint   = document.getElementById('stock-hint');
        const qtyInput    = document.getElementById('quantity');

        if (selectedOpt && selectedOpt.dataset.stock !== undefined) {
            const stock = parseInt(selectedOpt.dataset.stock);
            qtyInput.max = stock;
            stockHint.textContent = `Maksimal penjualan: ${stock} pcs`;
            if (stock <= 0) {
                stockHint.className = 'text-xs text-rose-600 mt-1 font-semibold';
                stockHint.textContent = `⚠️ Stok barang ini habis (${stock} pcs)`;
            } else {
                stockHint.className = 'text-xs text-slate-500 mt-1 font-medium';
            }
        } else {
            qtyInput.removeAttribute('max');
            stockHint.textContent = '';
        }
    }
</script>
@endpush
