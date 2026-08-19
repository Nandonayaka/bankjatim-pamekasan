@extends('layouts.app')

@section('title', 'stock')
@section('page_title', 'stock')
@section('page_subtitle', 'Jumlah persediaan')

@section('content')

{{-- Sub-header Action Bar --}}
<div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-3.5 mb-5 sm:mb-6 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 min-h-[64px]">
    <form method="GET" action="{{ route('stock.index') }}" class="flex items-center gap-2.5 w-full sm:w-auto flex-1 max-w-sm">
        <div class="relative w-full">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" name="search" class="w-full bg-white border border-slate-300 rounded-full py-2 pl-10 pr-4 text-xs text-slate-800 outline-none focus:border-[#C8102E] focus:ring-2 focus:ring-[#C8102E]/15 transition-all placeholder:text-slate-400" placeholder="Search"
                   value="{{ $search }}">
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
        <table class="min-w-[760px] w-full text-left border-collapse table-fixed">
            <thead>
                <tr class="bg-[#C8102E] text-white h-[40px] rounded-t-[8px]">
                    <th class="w-[12%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle first:rounded-tl-[8px]">Tgl</th>
                    <th class="w-[24%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Nama barang</th>
                    <th class="w-[15%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Sisa barang bulan lalu</th>
                    <th class="w-[12%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Kulaan</th>
                    <th class="w-[12%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Terjual</th>
                    <th class="w-[15%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle">Stok akhir</th>
                    <th class="w-[10%] h-[40px] px-3 sm:px-4 text-[12px] font-semibold text-white leading-none tracking-normal align-middle text-right last:rounded-tr-[8px]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-[#4B5563]">
                @forelse($stocks as $i => $inv)
                    @php
                        $stokAkhir = $inv->final_stock;
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors h-[52px]">
                        <td class="py-3 px-3 sm:px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">1 / {{ \Carbon\Carbon::createFromFormat('Y-m', $inv->period)->format('m / y') }}</td>
                        <td class="py-3 px-3 sm:px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">{{ strtolower($inv->item?->item_name ?? '-') }}</td>
                        <td class="py-3 px-3 sm:px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">{{ number_format($inv->initial_stock, 0, ',', '.') }} PCS</td>
                        <td class="py-3 px-3 sm:px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">{{ number_format($inv->total_in, 0, ',', '.') }} pcs</td>
                        <td class="py-3 px-3 sm:px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">{{ number_format($inv->total_out, 0, ',', '.') }} PCS</td>
                        <td class="py-3 px-3 sm:px-4 text-[14px] font-medium text-[#4B5563] border-b border-slate-200 align-middle">{{ number_format($stokAkhir, 0, ',', '.') }} PCS</td>
                        <td class="py-3 px-3 sm:px-4 text-right border-b border-slate-200 space-x-1 align-middle">
                            <button type="button" class="btn-edit-stock px-2.5 py-1 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-md text-xs font-semibold inline-flex items-center gap-1 transition-colors"
                                    data-id="{{ $inv->id }}"
                                    data-name="{{ $inv->item?->item_name }}"
                                    data-stock="{{ $inv->initial_stock }}"
                                    data-action="{{ route('stock.update', $inv->id) }}">
                                Edit
                            </button>
                            <form method="POST" action="{{ route('stock.destroy', $inv->id) }}"
                                  onsubmit="return confirm('Hapus data stok ini?')" class="inline-block">
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
                        <td colspan="7" class="py-10 text-center text-[14px] font-medium text-slate-400">
                            Belum ada data persediaan stok
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($stocks->hasPages())
        <div class="flex items-center justify-center gap-1.5 mt-6 text-xs font-bold flex-wrap">
            @if($stocks->onFirstPage())
                <span class="w-7 h-7 flex items-center justify-center text-slate-300 pointer-events-none">‹</span>
            @else
                <a href="{{ $stocks->previousPageUrl() }}" class="w-7 h-7 flex items-center justify-center text-[#C8102E] hover:bg-rose-50 rounded">‹</a>
            @endif

            @foreach($stocks->getUrlRange(1, min(3, $stocks->lastPage())) as $page => $url)
                @if($page == $stocks->currentPage())
                    <span class="w-7 h-7 bg-[#C8102E] text-white rounded flex items-center justify-center shadow-xs">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-7 h-7 text-slate-700 hover:bg-slate-100 rounded flex items-center justify-center">{{ $page }}</a>
                @endif
            @endforeach

            @if($stocks->lastPage() > 3)
                <span class="w-7 h-7 flex items-center justify-center text-slate-400">...</span>
                <a href="{{ $stocks->url($stocks->lastPage()) }}" class="w-7 h-7 text-slate-700 hover:bg-slate-100 rounded flex items-center justify-center">{{ $stocks->lastPage() }}</a>
            @endif

            @if($stocks->hasMorePages())
                <a href="{{ $stocks->nextPageUrl() }}" class="w-7 h-7 flex items-center justify-center text-[#C8102E] hover:bg-rose-50 rounded">›</a>
            @else
                <span class="w-7 h-7 flex items-center justify-center text-slate-300 pointer-events-none">›</span>
            @endif
        </div>
    @endif
</div>

{{-- Modal: Tambah Barang / Sisa Barang --}}
<div class="modal-overlay fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 flex items-center justify-center p-3.5 sm:p-5" id="modal-overlay">
    <div class="modal-card bg-white border border-slate-200 rounded-2xl w-full max-w-lg shadow-2xl p-5 sm:p-6 relative max-h-[90vh] overflow-y-auto">
        <button class="absolute top-4 sm:top-5 right-4 sm:right-5 text-slate-400 hover:text-slate-700 transition-colors" id="btn-close-modal" aria-label="Tutup">
            <svg class="w-5 h-5 stroke-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>

        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 mb-5 sm:mb-6">Tambah barang</h3>

        <form method="POST" action="{{ route('stock.store') }}" id="stock-create-form">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5" for="period">Tanggal</label>
                    <select name="period" id="period" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 outline-none focus:border-[#C8102E]" required>
                        @foreach($monthOptions as $opt)
                            <option value="{{ $opt['value'] }}" {{ $month === $opt['value'] ? 'selected' : '' }}>
                                {{ $opt['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5" for="item_name">Nama barang</label>
                    <input type="text" name="item_name" id="item_name"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 outline-none focus:border-[#C8102E] placeholder:text-slate-400"
                           placeholder="......" required
                           autocomplete="off" list="stock-item-suggestions">
                    <datalist id="stock-item-suggestions">
                        @foreach($allItems as $item)
                            <option value="{{ $item->item_name }}">
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5" for="initial_stock">Jumlah barang</label>
                    <input type="number" name="initial_stock" id="initial_stock"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 outline-none focus:border-[#C8102E] placeholder:text-slate-400"
                           min="0" placeholder="0" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nominal</label>
                <input type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-400" placeholder="RP .000" disabled>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-[10px] px-[20px] py-[10px] h-[37px] bg-[#C8102E] hover:bg-[#a30a25] text-white rounded-[10px] text-xs font-bold shadow-md transition-all">
                    + Tambah
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Edit Sisa Barang --}}
<div class="modal-overlay fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 flex items-center justify-center p-3.5 sm:p-5" id="edit-modal-overlay">
    <div class="modal-card bg-white border border-slate-200 rounded-2xl w-full max-w-md shadow-2xl p-5 sm:p-6 relative max-h-[90vh] overflow-y-auto">
        <button class="absolute top-4 sm:top-5 right-4 sm:right-5 text-slate-400 hover:text-slate-700 transition-colors" id="btn-close-edit-modal" aria-label="Tutup">
            <svg class="w-5 h-5 stroke-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>

        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 mb-5 sm:mb-6" id="edit-modal-title">Edit sisa barang</h3>

        <form method="POST" action="" id="edit-stock-form">
            @csrf
            @method('PUT')
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama barang</label>
                    <input type="text" id="edit-item-name" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-600" disabled>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5" for="edit-initial-stock">Sisa barang bulan lalu</label>
                    <input type="number" name="initial_stock" id="edit-initial-stock"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-[#C8102E]"
                           min="0" required>
                </div>
            </div>
            <div class="flex justify-end gap-2.5">
                <button type="button" class="w-full sm:w-auto px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-xl text-xs font-semibold" id="btn-cancel-edit">Batal</button>
                <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-[#C8102E] hover:bg-[#a30a25] text-white rounded-xl text-xs font-bold shadow-md transition-all">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Create Modal
    const overlay   = document.getElementById('modal-overlay');
    const openBtn   = document.getElementById('btn-open-modal');
    const closeBtn  = document.getElementById('btn-close-modal');

    function openModal()  { overlay.classList.add('open'); }
    function closeModal() { overlay.classList.remove('open'); }

    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });

    // Edit Modal
    const editOverlay   = document.getElementById('edit-modal-overlay');
    const editCloseBtn  = document.getElementById('btn-close-edit-modal');
    const editCancelBtn = document.getElementById('btn-cancel-edit');
    const editForm      = document.getElementById('edit-stock-form');
    const editItemName  = document.getElementById('edit-item-name');
    const editStockInput= document.getElementById('edit-initial-stock');

    function openEditModal(actionUrl, name, stock) {
        editForm.action = actionUrl;
        editItemName.value = name;
        editStockInput.value = stock;
        editOverlay.classList.add('open');
    }
    function closeEditModal() { editOverlay.classList.remove('open'); }

    document.querySelectorAll('.btn-edit-stock').forEach(btn => {
        btn.addEventListener('click', function() {
            openEditModal(this.dataset.action, this.dataset.name, this.dataset.stock);
        });
    });

    editCloseBtn.addEventListener('click', closeEditModal);
    editCancelBtn.addEventListener('click', closeEditModal);
    editOverlay.addEventListener('click', e => { if (e.target === editOverlay) closeEditModal(); });

    @if($errors->any()) openModal(); @endif
</script>
@endpush
